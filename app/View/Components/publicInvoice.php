<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class publicInvoice extends Component
{
    /**
     * Create a new component instance.
     */
    public $invoice;
    public $isEmail;
    public function __construct($invoice, $isEmail)
    {
        $this->invoice = $invoice;
        $this->isEmail = $isEmail;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.public-invoice');
    }
}
