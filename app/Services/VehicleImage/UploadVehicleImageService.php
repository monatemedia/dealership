<?php

// app/Services/VehicleImage/UploadVehicleImageService.php

namespace App\Services\VehicleImage;

use App\Jobs\ProcessVehicleImage;
use App\Models\Vehicle;
use App\Models\VehicleImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadVehicleImageService
{
    /**
     * Upload one or multiple images for a vehicle.
     *
     * @param UploadedFile|UploadedFile[] $images
     * @param Vehicle $vehicle
     * @return VehicleImage[]  // Return array of VehicleImage objects
     */
    public function handle($images, Vehicle $vehicle): array
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

            $vehicleImage = VehicleImage::create([
                'vehicle_id' => $vehicle->id,
                'original_filename' => $file->getClientOriginalName(),
                'temp_file_path' => $fullTempPath,
                'image_path' => '',
                'position' => 0,
                'status' => 'pending',
            ]);

            ProcessVehicleImage::dispatch($vehicleImage->id);
            $uploaded[] = $vehicleImage;

            Log::info("Uploaded vehicle image", [
                'vehicle_id' => $vehicle->id,
                'filename' => $file->getClientOriginalName(),
                'temp_file_path' => $fullTempPath,
            ]);
        }

        return $uploaded;
    }
}
