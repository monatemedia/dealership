<?php

// app/Services/CarImage/UploadCarImageService.php

namespace App\Services\CarImage;

use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadCarImageService
{
    /**
     * @param UploadedFile[] $images
     * @return int number uploaded
     */
    public function handle(array $images, Car $car): int
    {
        $count = 0;

        try {
            foreach ($images as $file) {
                if (!($file instanceof UploadedFile)) {
                    continue;
                }

                $filename = uniqid('', true) . '.' . strtolower($file->getClientOriginalExtension());
                $path = $file->storeAs("cars/{$car->id}", $filename, 'public');

                CarImage::create([
                    'car_id' => $car->id,
                    'original_filename' => $file->getClientOriginalName(),
                    'image_path' => $path,
                    'position' => 0, // normalized later
                    'status' => 'completed',
                ]);

                $count++;

                Log::info("Uploaded car image", [
                    'car_id' => $car->id,
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                ]);
            }

            return $count;

        } catch (\Throwable $e) {
            Log::error("Failed to upload car images", [
                'car_id' => $car->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
