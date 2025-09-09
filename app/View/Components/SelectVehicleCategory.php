<?php

// app/View/Components/SelectVehicleCategory.php

namespace App\View\Components;

use App\Models\VehicleCategory;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class SelectVehicleCategory extends Component
{
    public ?Collection $vehicleCategories;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->vehicleCategories = Cache::rememberForever('vehicle_categories', function () {
            return VehicleCategory::orderBy('id')->get();
        });
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select-vehicle-category', [
            'vehicleCategories' => $this->vehicleCategories,
        ]);
    }
}
