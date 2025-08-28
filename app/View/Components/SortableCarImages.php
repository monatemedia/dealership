<?php

// resources/views/components/sortable-car-images.blade.php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Car;

class SortableCarImages extends Component
{
    public ?Car $car;
    public array $images;
    public string $mode;

    /**
     * Create a new component instance.
     *
     * @param Car $car
     */
    public function __construct(Car $car = null, string $mode = 'normal')
    {
        $this->car = $car;
        $this->mode = $mode;

        $this->images = $car
            ? $car->images->map(fn($image) => [
                'id' => (string) $image->id,
                'image' => $image->getUrl(),
                'car_id' => $image->car_id,
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
        return view('components.sortable-car-images');
    }
}
