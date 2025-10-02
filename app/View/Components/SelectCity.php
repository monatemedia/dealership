<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectCity extends Component
{
    /**
     * Create a new component instance.
     *
     * Note: We don't load cities here anymore.
     * The Alpine.js component handles data loading via API search.
     */
    public function __construct()
    {
        // Empty - data is loaded via API on search
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select-city');
    }
}
