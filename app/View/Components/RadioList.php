<?php
// app/View/Components/RadioList.php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class RadioList extends Component
{
    public $items;
    public $name;
    public $value;
    public $label;

    public function __construct($items, $name, $value = null, $label = null)
    {
        $this->items = $items;
        $this->name = $name;
        $this->value = $value;
        $this->label = $label;
    }

    public function render(): View
    {
        return view('components.radio-list');
    }
}
