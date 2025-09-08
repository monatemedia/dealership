<?php

// resources/views/components/sortable-vehicle-images.blade.php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Vehicle;

class SortableVehicleImages extends Component
{
    public ?Vehicle $vehicle;
    public array $images;
    public string $mode;

    /**
     * Create a new component instance.
     *
     * @param Vehicle $vehicle
     */
    public function __construct(Vehicle $vehicle = null, string $mode = 'normal')
    {
        $this->vehicle = $vehicle;
        $this->mode = $mode;

        $this->images = $vehicle
            ? $vehicle->images->map(fn($image) => [
                'id' => (string) $image->id,
                'image' => $image->getUrl(),
                'vehicle_id' => $image->vehicle_id,
                'original_filename' => $image->original_filename,
                'status' => $image->status ?? 'valid',
            ])->toArray() // convert to array for Blade
            : []; // empty for create
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sortable-vehicle-images');
    }
}
