<?php

namespace App\View\Components;

use App\Models\VehicleType;
use App\Models\SubCategory;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class RadioListVehicleType extends Component
{
    public Collection $types;

    /**
     * Create a new component instance.
     */
    public function __construct(SubCategory $subCategory)
    {
        // Only get vehicle types for the given sub-category
        $this->types = $subCategory->vehicleTypes()->orderBy('name')->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.radio-list-vehicle-type');
    }
}
