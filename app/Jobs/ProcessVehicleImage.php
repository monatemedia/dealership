<?php // app/Jobs/ProcessVehicleImage.php

namespace App\Jobs;

use App\Models\VehicleImage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\ImageProcessingService;
use Throwable;

class ProcessVehicleImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $vehicleImageId;

    public function __construct(int $vehicleImageId)
    {
        $this->vehicleImageId = $vehicleImageId;
    }

    public function handle(ImageProcessingService $processor)
    {
        $vehicleImage = VehicleImage::findOrFail($this->vehicleImageId);

        // Mark as processing
        $vehicleImage->update(['status' => 'processing']);

        // Validate the file path before attempting to process
        if (empty($vehicleImage->temp_file_path) || !file_exists($vehicleImage->temp_file_path)) {
            Log::error("ProcessVehicleImage: File missing for VehicleImage ID {$vehicleImage->id}", [
                'temp_file_path' => $vehicleImage->temp_file_path,
            ]);

            // Mark as failed due to missing file
            $vehicleImage->update(['status' => 'failed']);
            return; // stop processing
        }

        try {
            // Process the image using the service
            $processor->process($vehicleImage);

            // Mark as completed
            $vehicleImage->update(['status' => 'completed']);

            // Clean up temp file
            if (!empty($vehicleImage->temp_file_path) && file_exists($vehicleImage->temp_file_path)) {
                @unlink($vehicleImage->temp_file_path); // @ suppresses any rare unlink error
            }

        } catch (\Throwable $e) {
            // Log the error for debugging
            Log::error("ProcessVehicleImage: Error processing VehicleImage ID {$vehicleImage->id}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Mark as failed
            $vehicleImage->update(['status' => 'failed']);
            throw $e; // Let Laravel's job failure handling catch it
        }
    }

    public function failed(Throwable $exception): void
    {
        // Ensure status is set to failed if job crashes unexpectedly
        VehicleImage::where('id', $this->vehicleImageId)->update([
            'status' => 'failed'
        ]);

        Log::error("ProcessVehicleImage: Job permanently failed for VehicleImage ID {$this->vehicleImageId}", [
            'error' => $exception->getMessage(),
        ]);
    }
}
