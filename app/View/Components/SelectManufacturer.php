<?php

namespace App\View\Components;

use App\Models\Manufacturer;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class SelectManufacturer extends Component
{
    public ?Collection $manufacturers;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->manufacturers = Cache::rememberForever('manufacturers', function () {
            return Manufacturer::orderBy('name')->get();
        });
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select-manufacturer');
    }
}
