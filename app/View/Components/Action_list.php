<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Action_list extends Component
{
    public $view, $edit, $delete;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($view, $edit, $delete)
    {
        $this->view = $view;
        $this->edit = $edit;
        $this->delete = $delete;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.action_list');
    }
}
