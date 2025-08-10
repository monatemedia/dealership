<?php

namespace App\Jobs;

use App\Models\CarImage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\ImageProcessingService;
use Exception;

class ProcessCarImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $carImageId;

    public function __construct(int $carImageId)
    {
        $this->carImageId = $carImageId;
    }

    public function handle(ImageProcessingService $processor)
    {
        $carImage = CarImage::findOrFail($this->carImageId);

        // Mark as processing
        $carImage->update(['status' => 'processing']);

        // Validate the file path before attempting to process
        if (empty($carImage->temp_file_path) || !file_exists($carImage->temp_file_path)) {
            Log::error("ProcessCarImage: File missing for CarImage ID {$carImage->id}", [
                'temp_file_path' => $carImage->temp_file_path,
            ]);

            // Mark as failed due to missing file
            $carImage->update(['status' => 'failed']);
            return; // stop processing
        }

        try {
            // Process the image using the service
            $processor->process($carImage);

            // Mark as completed
            $carImage->update(['status' => 'completed']);

            // Clean up temp file
            unlink($carImage->temp_file_path);

        } catch (\Throwable $e) {
            // Log the error for debugging
            Log::error("ProcessCarImage: Error processing CarImage ID {$carImage->id}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Mark as failed
            $carImage->update(['status' => 'failed']);
            throw $e; // Let Laravel's job failure handling catch it
        }
    }

    public function failed(Exception $exception): void
    {
        // Ensure status is set to failed if job crashes unexpectedly
        CarImage::where('id', $this->carImageId)->update([
            'status' => 'failed'
        ]);

        Log::error("ProcessCarImage: Job permanently failed for CarImage ID {$this->carImageId}", [
            'error' => $exception->getMessage(),
        ]);
    }
}
