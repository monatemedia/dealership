<?php

// app/Services/ImageProcessingService.php

namespace App\Services;

use App\Models\VehicleImage;
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
     * Process a vehicle image: resize, convert to WebP, optimize, and store.
     *
     * @param  VehicleImage  $vehicleImage
     * @return void
     * @throws Exception if file does not exist or processing fails
     */
    public function process(VehicleImage $vehicleImage): void
    {
        $filePath = str_replace('\\', '/', $vehicleImage->temp_file_path);

        // Ensure the file exists before proceeding
        if (empty($filePath) || !file_exists($filePath)) {
            Log::error("ImageProcessingService: Missing file for VehicleImage ID {$vehicleImage->id}", [
                'temp_file_path' => $filePath,
            ]);
            throw new Exception("File does not exist at path: {$filePath}");
        }

        $width = 600;
        $filename = Str::uuid() . ".webp";
        $path = "images/vehicles/" . $filename;

        try {
            // Read and resize the image
            $image = Image::read($filePath)
                ->scaleDown($width)
                ->encode(new WebpEncoder(quality: 80));

            // Store the processed image in the public disk
            Storage::disk('public')->put($path, (string) $image);

            // Optimize the stored file
            $this->optimizer->optimize(Storage::disk('public')->path($path));

            // Update the VehicleImage record with the new image path
            $vehicleImage->update([
                'image_path' => $path
            ]);

        } catch (Exception $e) {
            Log::error("ImageProcessingService: Failed to process VehicleImage ID {$vehicleImage->id}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e; // Let job failure handling catch it
        }
    }
}
