<?php

// app/Services/VehicleImage/VehicleImageService.php

namespace App\Services\VehicleImage;

use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VehicleImageService
{
    protected UploadVehicleImageService $uploadService;
    protected DeleteVehicleImageService $deleteService;
    protected UpdateVehicleImagePositionService $positionService;

    public function __construct(
        UploadVehicleImageService $uploadService,
        DeleteVehicleImageService $deleteService,
        UpdateVehicleImagePositionService $positionService
    ) {
        $this->uploadService = $uploadService;
        $this->deleteService = $deleteService;
        $this->positionService = $positionService;
    }

    /**
     * Sync images for a vehicle.
     *
     * @param Vehicle $vehicle
     * @param array $imagesData
     */
    public function sync(Vehicle $vehicle, array $imagesData): void
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
            $newUploads = []; // key = temp frontend ID, value = VehicleImage instance

            Log::debug("Starting image sync", [
                'vehicle_id' => $vehicle->id,
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
                        $this->deleteService->handle([(int) $item['id']], $vehicle);
                        break;

                    case 'upload':
                        if (isset($item['file'])) {
                            // ✅ Upload file using UploadVehicleImageService
                            $uploaded = $this->uploadService->handle($item['file'], $vehicle);
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
                'vehicle_id' => $vehicle->id,
                'map' => $positionMap,
            ]);

            // 3️⃣ Apply positions & normalize
            if (!empty($positionMap)) {
                $this->positionService->handle($positionMap, $vehicle);
                // ✅ Pass positionMap to normalize to respect frontend ordering
                $this->positionService->normalize($vehicle, $positionMap);
            }

            DB::commit();
            Log::info("Vehicle images synced successfully", ['vehicle_id' => $vehicle->id]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Vehicle image sync failed", [
                'vehicle_id' => $vehicle->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
