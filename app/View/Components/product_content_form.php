<?php

namespace App\View\Components;

use Illuminate\View\Component;

class product_content_form extends Component
{
    public $product, $categories;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($product, $categories)
    {
        $this->product = $product;
        $this->categories = $categories;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.product_content_form');
    }
}
