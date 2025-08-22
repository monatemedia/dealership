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

    /**
     * Sync images for a car.
     *
     * @param Car $car
     * @param array $imagesData
     */
    public function sync(Car $car, array $imagesData): void
    {
        /**
         * $imagesData = [
         *   ['id' => 12, 'action' => 'keep', 'position' => 1],
         *   ['id' => 'temp-1', 'action' => 'upload', 'file' => UploadedFile, 'position' => 2],
         *   ['id' => 15, 'action' => 'delete'],
         * ]
         */

        DB::beginTransaction();

        try {

            $positionMap = [];
            $newUploads = []; // key = temp frontend ID, value = CarImage instance

            Log::debug("Starting image sync", [
                'car_id' => $car->id,
                'payload' => $imagesData,
            ]);

            // 1️⃣ Process Deletes & Uploads
            foreach ($imagesData as $item) {
                if (!isset($item['action'], $item['id'])) {
                    Log::warning("Skipping invalid item in payload", ['item' => $item]);
                    continue;
                }

                switch ($item['action']) {
                    case 'delete':
                        Log::debug("Deleting image", ['id' => $item['id']]);
                        $this->deleteService->handle([(int) $item['id']], $car);
                        break;

                    case 'upload':
                        if (isset($item['file'])) {
                            // ✅ Upload file using UploadCarImageService
                            $uploaded = $this->uploadService->handle($item['file'], $car);
                            if (!empty($uploaded)) {
                                $newUploads[$item['id']] = $uploaded[0];
                                Log::debug("Uploaded new image", [
                                    'frontend_temp_id' => $item['id'],
                                    'db_id' => $uploaded[0]->id,
                                ]);
                            }
                        }
                        break;

                    case 'keep':
                        // nothing to do here yet
                        break;
                }
            }

            // 2️⃣ Build position map directly from frontend payload
            foreach ($imagesData as $item) {
                if (!isset($item['action'], $item['id'], $item['position'])) {
                    continue;
                }

                switch ($item['action']) {
                    case 'keep':
                        $positionMap[(int) $item['id']] = (int) $item['position'];
                        break;

                    case 'upload':
                        if (isset($newUploads[$item['id']])) {
                            // ✅ Map the new DB ID to frontend position
                            $positionMap[$newUploads[$item['id']]->id] = (int) $item['position'];
                        }
                        break;
                }
            }

            Log::debug("Built position map", [
                'car_id' => $car->id,
                'map' => $positionMap,
            ]);

            // 3️⃣ Apply positions & normalize
            if (!empty($positionMap)) {
                $this->positionService->handle($positionMap, $car);
                // ✅ Pass positionMap to normalize to respect frontend ordering
                $this->positionService->normalize($car, $positionMap);
            }

            DB::commit();
            Log::info("Car images synced successfully", ['car_id' => $car->id]);

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
