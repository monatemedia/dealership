<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ServiceCard extends Component
{
    public string $href;
    public string $image;
    public string $title;
    public string $description;

    /**
     * Create a new component instance.
     */
    public function __construct(string $href, string $image, string $title, string $description)
    {
        $this->href = $href;
        $this->image = $image;
        $this->title = $title;
        $this->description = $description;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.service-card');
    }
}
