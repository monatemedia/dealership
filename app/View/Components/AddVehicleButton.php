<?php // app/View/Components/AddVehicleButton.php

namespace App\View\Components;

use App\Models\VehicleCategory;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class AddVehicleButton extends Component
{
    public ?VehicleCategory $category;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // Detect if we're on category.show
        $this->category = null;

        if (Route::currentRouteName() === 'category.show') {
            $this->category = request()->route('category');
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.add-vehicle-button');
    }
}
