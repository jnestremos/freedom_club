<?php

namespace App\View\Components;

use Illuminate\View\Component;

class empButtonLink extends Component
{
    public $link;
    public $title;
    public $toggle;
    public $target;
    public $dataCollection;
    public $disabled;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title, $target = null, $link = null, $toggle = 'false', $dataCollection = null, $disabled = 'false')
    {
        $this->link = $link;
        $this->title = $title;
        $this->toggle = $toggle;
        $this->target = $target;
        $this->dataCollection = $dataCollection;
        $this->disabled = $disabled;
    }



    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.emp-button-link');
    }
}
