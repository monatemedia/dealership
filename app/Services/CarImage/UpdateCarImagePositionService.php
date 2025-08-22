<?php

// app/Services/CarImage/UpdateCarImagePositionService.php

namespace App\Services\CarImage;

use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Support\Facades\Log;

class UpdateCarImagePositionService
{
    /**
     * Update positions for images that belong to this car.
     *
     * @param array $positions  // [id => position, ...]
     * @param Car $car
     * @return int
     */
    public function handle(array $positions, Car $car): int
    {
        $updated = 0;

        try {
            foreach ($positions as $id => $pos) {
                $id = (int) $id;
                $pos = max(1, (int) $pos); // ensure >0

                $changed = CarImage::where('id', $id)
                    ->where('car_id', $car->id)
                    ->update(['position' => $pos]);

                if ($changed) {
                    $updated++;
                }
            }

            Log::info("Updated image positions", [
                'car_id' => $car->id,
                'updated' => $updated,
            ]);

            return $updated;

        } catch (\Throwable $e) {
            Log::error("Failed to update image positions", [
                'car_id' => $car->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Normalize positions so they are sequential 1..N while preserving frontend order.
     *
     * @param Car $car
     * @param array $positionMap Optional: use this map to enforce frontend order [id => position]
     */
    public function normalize(Car $car, array $positionMap = []): void
    {
        try {
            if (!empty($positionMap)) {
                // Sort by frontend positions
                asort($positionMap);

                $pos = 1;
                foreach (array_keys($positionMap) as $id) {
                    CarImage::where('id', $id)
                        ->where('car_id', $car->id)
                        ->update(['position' => $pos]);
                    $pos++;
                }

                Log::info("Normalized image positions using frontend positionMap", [
                    'car_id' => $car->id,
                    'map' => $positionMap,
                ]);
            } else {
                // fallback: normalize by current DB order
                $images = CarImage::where('car_id', $car->id)
                    ->orderBy('position')
                    ->orderBy('id')
                    ->get(['id', 'position']);

                $pos = 1;
                foreach ($images as $img) {
                    if ($img->position !== $pos) {
                        CarImage::where('id', $img->id)->update(['position' => $pos]);
                    }
                    $pos++;
                }

                Log::info('Normalized image positions fallback', [
                    'car_id' => $car->id,
                    'total' => count($images),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error("Failed to normalize image positions", [
                'car_id' => $car->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
