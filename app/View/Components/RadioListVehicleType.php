<?php

namespace App\View\Components;

use App\Models\Subcategory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RadioListVehicleType extends Component
{
    public $types;
    public $value;

    /**
     * Create a new component instance.
     */
    public function __construct(Subcategory $subcategory, $value = null)
    {
        $this->types = $subcategory->vehicleTypes()->orderBy('name')->get();
        $this->value = $value; // ← store currently selected value (for edit)
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|string
    {
        return view('components.radio-list-vehicle-type', [
            'types' => $this->types,
            'selectedValue' => $this->value,
        ]);
    }
}
