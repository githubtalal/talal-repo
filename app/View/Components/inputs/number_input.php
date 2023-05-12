<?php

namespace App\View\Components;

use Illuminate\View\Component;

class number_input extends Component
{
    public $label, $name, $price_value;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($label, $name, $price_value)
    {
        $this->label = $label;
        $this->name = $name;
        $this->price_value = $price_value;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.inputs.number_input');
    }
}
