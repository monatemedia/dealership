<?php

// resources\views\components\sortable-car-images.blade.php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SortableCarImages extends Component
{
    public $images;

    /**
     * Create a new component instance.
     *
     * @param  \Illuminate\Support\Collection|array  $images
     */
    public function __construct($images)
    {
        // Transform images to JS-ready format
        $this->images = collect($images)->map(function ($image) {
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
