<?php

namespace App\View\Components;

use App\Models\Province;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class SelectProvince extends Component
{
    public Collection $provinces;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->provinces = Province::orderBy('name')->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select-province');
    }
}
