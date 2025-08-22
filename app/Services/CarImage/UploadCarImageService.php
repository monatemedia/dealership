<?php

// app/Services/CarImage/UploadCarImageService.php

namespace App\Services\CarImage;

use App\Jobs\ProcessCarImage;
use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadCarImageService
{
    /**
     * Upload one or multiple images for a car.
     *
     * @param UploadedFile|UploadedFile[] $images
     * @param Car $car
     * @return CarImage[]  // Return array of CarImage objects
     */
    public function handle($images, Car $car): array
    {
        $uploaded = [];

        // Normalize to array
        $images = is_array($images) ? $images : [$images];

        foreach ($images as $file) {
            if (!($file instanceof UploadedFile)) {
                continue;
            }

            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $storedPath = Storage::disk('private')->putFileAs('processing_queue', $file, $filename);
            $fullTempPath = str_replace('\\', '/', Storage::disk('private')->path($storedPath));

            $carImage = CarImage::create([
                'car_id' => $car->id,
                'original_filename' => $file->getClientOriginalName(),
                'temp_file_path' => $fullTempPath,
                'image_path' => '',
                'position' => 0,
                'status' => 'pending',
            ]);

            ProcessCarImage::dispatch($carImage->id);
            $uploaded[] = $carImage;

            Log::info("Uploaded car image", [
                'car_id' => $car->id,
                'filename' => $file->getClientOriginalName(),
                'temp_file_path' => $fullTempPath,
            ]);
        }

        return $uploaded;
    }
}
