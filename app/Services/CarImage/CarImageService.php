<?php

// app/Services/CarImage/CarImageService.php

namespace App\Services\CarImage;

use App\Models\Car;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CarImageService
{
    protected UploadCarImageService $uploadService;
    protected DeleteCarImageService $deleteService;
    protected UpdateCarImagePositionService $positionService;

    public function __construct(
        UploadCarImageService $uploadService,
        DeleteCarImageService $deleteService,
        UpdateCarImagePositionService $positionService
    ) {
        $this->uploadService = $uploadService;
        $this->deleteService = $deleteService;
        $this->positionService = $positionService;
    }

    public function sync(Car $car, array $newImages, array $deleteIds, array $positions): void
    {
        DB::beginTransaction();

        try {
            $uploaded = 0;
            $deleted = 0;
            $reordered = 0;

            if (!empty($newImages)) {
                $uploaded = $this->uploadService->handle($newImages, $car); // return count
            }

            if (!empty($deleteIds)) {
                $deleted = $this->deleteService->handle($deleteIds, $car); // return count
            }

            if (!empty($positions)) {
                $reordered = $this->positionService->handle($positions, $car); // return count updated
            }

            // Normalize positions to 1..N after all changes
            $this->positionService->normalize($car);

            DB::commit();

            Log::info("Car images synced successfully", [
                'car_id' => $car->id,
                'uploaded' => $uploaded,
                'deleted' => $deleted,
                'reordered' => $reordered,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Car image sync failed", [
                'car_id' => $car->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
