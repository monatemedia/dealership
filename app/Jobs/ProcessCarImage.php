<?php

namespace App\Jobs;

use App\Models\Car;
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

    protected string $tempFilePath;
    protected int $carId;
    protected int $position;

    public function __construct(string $tempFilePath, int $carId, int $position)
    {
        $this->tempFilePath = $tempFilePath;
        $this->carId = $carId;
        $this->position = $position;
    }

    public function handle(ImageProcessingService $processor)
    {
        try {
            Log::info('Processing image', [
                'tempFilePath' => $this->tempFilePath,
                'carId' => $this->carId,
                'position' => $this->position,
            ]);

            $car = Car::findOrFail($this->carId);
            $processor->process($this->tempFilePath, $car, $this->position);

            if (file_exists($this->tempFilePath)) {
                unlink($this->tempFilePath);
                Log::info("Temp file deleted: {$this->tempFilePath}");
            }

        } catch (Exception $e) {
            Log::error('Error processing car image', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Rethrow to mark job as failed
            throw $e;
        }
    }

    public function failed(Exception $exception): void
    {
        Log::critical('Job failed permanently', [
            'carId' => $this->carId,
            'error' => $exception->getMessage(),
        ]);
    }
}
