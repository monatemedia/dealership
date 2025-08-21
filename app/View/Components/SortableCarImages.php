<?php

// resources/views/components/sortable-car-images.blade.php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Car;

class SortableCarImages extends Component
{
    public Car $car;
    public array $images;

    /**
     * Create a new component instance.
     *
     * @param Car $car
     */
    public function __construct(Car $car)
    {
        $this->car = $car;

        // Transform images to JS-ready format
        $this->images = $car->images->map(function ($image) {
            return [
                'id' => (string) $image->id,          // JS needs string IDs
                'image' => $image->getUrl(),           // full URL
                'car_id' => $image->car_id,
                'original_filename' => $image->original_filename,
                'status' => $image->status ?? 'valid', // default if null
            ];
        })->toArray(); // Convert to array for Blade @json
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sortable-car-images');
    }
}
