<?php

namespace App\View\Components;

use Illuminate\View\Component;

class select_input extends Component
{
    public $label, $name, $items, $selected;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($label, $name, $items, $selected)
    {
        $this->label = $label;
        $this->name = $name;
        $this->items = $items;
        $this->selected = $selected;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.inputs.select_input');
    }
}
