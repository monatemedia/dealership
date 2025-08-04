<?php

namespace App\Services;

use App\Models\Car;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Illuminate\Support\Str;
use Intervention\Image\Encoders\WebpEncoder;

class ImageProcessingService
{
    protected $optimizer;

    public function __construct()
    {
        $this->optimizer = OptimizerChainFactory::create();
    }

    public function process(string $filePath, Car $car, int $position)
    {
        $width = 520;
        $filename = Str::uuid() . ".webp";
        $path = "images/cars/" . $filename;

        if (!file_exists($filePath)) {
            throw new \Exception("File does not exist at path: $filePath");
        }

        try {
            $image = Image::read($filePath)
                ->scaleDown($width) // Maintain aspect ratio, limit width to 520px
                ->encode(new WebpEncoder(quality: 80));
        } catch (\Exception $e) {
            logger()->error("Image processing failed: " . $e->getMessage());
            throw $e;
        }

        logger()->info("Image successfully processed", [
            'car_id' => $car->id,
            'position' => $position,
            'path' => $path,
        ]);

        Storage::disk('public')->put($path, (string) $image);

        $this->optimizer->optimize(Storage::disk('public')->path($path));

        $car->images()->create([
            'image_path' => $path,
            'position' => $position,
        ]);

        logger()->info("Image record created and file stored", [
            'car_id' => $car->id,
            'path' => $path,
        ]);
    }
}
