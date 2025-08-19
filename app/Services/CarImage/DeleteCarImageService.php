<?php

// app/Services/CarImage/DeleteCarImageService.php

namespace App\Services\CarImage;

use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeleteCarImageService
{
    /**
     * @return int number deleted
     */
    public function handle(array $deleteImageIds, Car $car): int
    {
        $count = 0;

        try {
            foreach ($deleteImageIds as $id) {
                $id = (int) $id;
                $image = CarImage::where('car_id', $car->id)->where('id', $id)->first();

                if ($image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                    $count++;

                    Log::info("Deleted car image", [
                        'car_id' => $car->id,
                        'image_id' => $id,
                    ]);
                }
            }

            return $count;

        } catch (\Throwable $e) {
            Log::error("Failed to delete car images", [
                'car_id' => $car->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
