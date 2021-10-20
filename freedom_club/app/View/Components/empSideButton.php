<?php

namespace App\View\Components;

use Illuminate\View\Component;

class empSideButton extends Component
{

    public $page;
    public $selected;


    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($page, $selected)
    {
        $this->page = $page;
        $this->selected = $selected;
    }

    public function highlightButton()
    {
        if (($this->page == 'Home' && $this->selected == 'dashboard.home') || ($this->page == 'Supplier Profiles' && $this->selected == 'dashboard.suppliers')
            || ($this->page == 'Employee Profiles' && $this->selected == 'dashboard.employees') || ($this->page == 'Stock Inventory' && $this->selected == 'dashboard.stocks')
            || ($this->page == 'Product Inventory' && $this->selected == 'dashboard.products') || ($this->page == 'Shipments' && $this->selected == 'dashboard.shipments')
            || ($this->page == 'Sales' && $this->selected == 'dashboard.sales') || ($this->page == 'Expenses' && $this->selected == 'dashboard.expenses')
            || ($this->page == 'Balance Sheet' && $this->selected == 'dashboard.balance') ||  ($this->page == 'Order Line' && $this->selected == 'dashboard.orders')
            || ($this->page == 'Stock Transfer Requests' && $this->selected == 'dashboard.transfer') ||  ($this->page == 'Supplier Purchase Records' && $this->selected == 'dashboard.purchases')
        ) {
            return  'border-left-color: white; border-left-width:10px; border-left-style:solid;';
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.emp-side-button');
    }
}
