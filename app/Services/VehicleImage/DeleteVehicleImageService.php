<?php

// app/Services/VehicleImage/DeleteVehicleImageService.php

namespace App\Services\VehicleImage;

use App\Models\Vehicle;
use App\Models\VehicleImage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeleteVehicleImageService
{
    /**
     * @return int number deleted
     */
    public function handle(array $deleteImageIds, Vehicle $vehicle): int
    {
        $count = 0;

        try {
            foreach ($deleteImageIds as $id) {
                $id = (int) $id;
                $image = VehicleImage::where('vehicle_id', $vehicle->id)->where('id', $id)->first();

                if ($image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                    $count++;

                    Log::info("Deleted vehicle image", [
                        'vehicle_id' => $vehicle->id,
                        'image_id' => $id,
                    ]);
                }
            }

            return $count;

        } catch (\Throwable $e) {
            Log::error("Failed to delete vehicle images", [
                'vehicle_id' => $vehicle->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
