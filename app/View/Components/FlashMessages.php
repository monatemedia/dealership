<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FlashMessages extends Component
{
    public array $messages;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // Convert Laravel session flashes into a consistent array format
        $this->messages = collect([
            session('success') ? ['type' => 'success', 'text' => session('success')] : null,
            session('warning') ? ['type' => 'warning', 'text' => session('warning')] : null,
            session('error') ? ['type' => 'error', 'text' => session('error')] : null,
        ])->filter()->values()->all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.flash-messages');
    }
}
