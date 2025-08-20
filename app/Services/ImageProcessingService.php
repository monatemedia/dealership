<?php

// app/Services/ImageProcessingService.php

namespace App\Services;

use App\Models\CarImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Encoders\WebpEncoder;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Illuminate\Support\Str;
use Exception;

class ImageProcessingService
{
    protected $optimizer;

    public function __construct()
    {
        $this->optimizer = OptimizerChainFactory::create();
    }

    /**
     * Process a car image: resize, convert to WebP, optimize, and store.
     *
     * @param  CarImage  $carImage
     * @return void
     * @throws Exception if file does not exist or processing fails
     */
    public function process(CarImage $carImage): void
    {
        $filePath = str_replace('\\', '/', $carImage->temp_file_path);

        // Ensure the file exists before proceeding
        if (empty($filePath) || !file_exists($filePath)) {
            Log::error("ImageProcessingService: Missing file for CarImage ID {$carImage->id}", [
                'temp_file_path' => $filePath,
            ]);
            throw new Exception("File does not exist at path: {$filePath}");
        }

        $width = 600;
        $filename = Str::uuid() . ".webp";
        $path = "images/cars/" . $filename;

        try {
            // Read and resize the image
            $image = Image::read($filePath)
                ->scaleDown($width)
                ->encode(new WebpEncoder(quality: 80));

            // Store the processed image in the public disk
            Storage::disk('public')->put($path, (string) $image);

            // Optimize the stored file
            $this->optimizer->optimize(Storage::disk('public')->path($path));

            // Update the CarImage record with the new image path
            $carImage->update([
                'image_path' => $path
            ]);

        } catch (Exception $e) {
            Log::error("ImageProcessingService: Failed to process CarImage ID {$carImage->id}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e; // Let job failure handling catch it
        }
    }
}
