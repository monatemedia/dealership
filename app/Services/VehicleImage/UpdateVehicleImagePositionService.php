<?php

// app/Services/VehicleImage/UpdateVehicleImagePositionService.php

namespace App\Services\VehicleImage;

use App\Models\Vehicle;
use App\Models\VehicleImage;
use Illuminate\Support\Facades\Log;

class UpdateVehicleImagePositionService
{
    /**
     * Update positions for images that belong to this vehicle.
     *
     * @param array $positions  // [id => position, ...]
     * @param Vehicle $vehicle
     * @return int
     */
    public function handle(array $positions, Vehicle $vehicle): int
    {
        $updated = 0;

        try {
            foreach ($positions as $id => $pos) {
                $id = (int) $id;
                $pos = max(1, (int) $pos); // ensure >0

                $changed = VehicleImage::where('id', $id)
                    ->where('vehicle_id', $vehicle->id)
                    ->update(['position' => $pos]);

                if ($changed) {
                    $updated++;
                }
            }

            Log::info("Updated image positions", [
                'vehicle_id' => $vehicle->id,
                'updated' => $updated,
            ]);

            return $updated;

        } catch (\Throwable $e) {
            Log::error("Failed to update image positions", [
                'vehicle_id' => $vehicle->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Normalize positions so they are sequential 1..N while preserving frontend order.
     *
     * @param Vehicle $vehicle
     * @param array $positionMap Optional: use this map to enforce frontend order [id => position]
     */
    public function normalize(Vehicle $vehicle, array $positionMap = []): void
    {
        try {
            if (!empty($positionMap)) {
                // Sort by frontend positions
                asort($positionMap);

                $pos = 1;
                foreach (array_keys($positionMap) as $id) {
                    VehicleImage::where('id', $id)
                        ->where('vehicle_id', $vehicle->id)
                        ->update(['position' => $pos]);
                    $pos++;
                }

                Log::info("Normalized image positions using frontend positionMap", [
                    'vehicle_id' => $vehicle->id,
                    'map' => $positionMap,
                ]);
            } else {
                // fallback: normalize by current DB order
                $images = VehicleImage::where('vehicle_id', $vehicle->id)
                    ->orderBy('position')
                    ->orderBy('id')
                    ->get(['id', 'position']);

                $pos = 1;
                foreach ($images as $img) {
                    if ($img->position !== $pos) {
                        VehicleImage::where('id', $img->id)->update(['position' => $pos]);
                    }
                    $pos++;
                }

                Log::info('Normalized image positions fallback', [
                    'vehicle_id' => $vehicle->id,
                    'total' => count($images),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error("Failed to normalize image positions", [
                'vehicle_id' => $vehicle->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
