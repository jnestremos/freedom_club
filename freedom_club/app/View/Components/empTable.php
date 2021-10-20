<?php

namespace App\View\Components;

use Illuminate\View\Component;

class empTable extends Component
{

    public $dataCollection;
    public $headers;
    public $title;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($dataCollection, $headers, $title)
    {
        $this->dataCollection = $dataCollection;
        $this->headers = $headers;
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.emp-table');
    }
}
