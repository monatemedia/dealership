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
     * @return int number updated
     */
    public function handle(array $positions, Car $car): int
    {
        $updated = 0;

        try {
            // Only existing IDs for this car
            $validIds = CarImage::where('car_id', $car->id)->pluck('id')->all();
            $validIds = array_map('intval', $validIds);

            foreach ($positions as $id => $pos) {
                $id = (int) $id;
                $pos = (int) $pos;

                if ($pos <= 0)
                    continue;
                if (!in_array($id, $validIds, true))
                    continue;

                $changed = CarImage::where('id', $id)
                    ->where('car_id', $car->id)
                    ->update(['position' => $pos]);

                if ($changed)
                    $updated++;
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
     * Normalize positions so they are 1..N in the current order.
     */
    public function normalize(Car $car): void
    {
        // Order by position asc, then id as a stable tie-breaker
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

        Log::info('Normalized image positions', [
            'car_id' => $car->id,
            'total' => count($images),
        ]);
    }
}
