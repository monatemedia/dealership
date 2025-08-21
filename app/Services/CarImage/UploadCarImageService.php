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

                // Generate a unique filename while preserving the extension
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();

                // Store the file in the private/processing_queue directory
                // 'private' here corresponds to storage/app/private
                $storedPath = Storage::disk('private')
                    ->putFileAs('processing_queue', $file, $filename);

                // Get the absolute full path to where the file was stored
                $fullTempPath = Storage::disk('private')->path($storedPath);

                // Normalize slashes for cross-OS compatibility (Windows vs Linux)
                $fullTempPath = str_replace('\\', '/', $fullTempPath);

                // Save record in DB — note: status is now 'pending' since
                // it’s queued for processing, not immediately completed
                $carImage = CarImage::create([
                    'car_id' => $car->id,
                    'original_filename' => $file->getClientOriginalName(),
                    'temp_file_path' => $fullTempPath, // critical for the job to find the file
                    'image_path' => '', // will be set after processing
                    'position' => 0, // normalized later
                    'status' => 'pending',
                ]);

                // Dispatch the processing job immediately after creation
                ProcessCarImage::dispatch($carImage->id);

                $count++;

                Log::info("Uploaded car image", [
                    'car_id' => $car->id,
                    'filename' => $file->getClientOriginalName(),
                    'temp_file_path' => $fullTempPath, // critical for the job to find the file
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
