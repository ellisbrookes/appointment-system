<?php
namespace App\View\Components;

use Illuminate\View\Component;

class Pricing extends Component
{
    public $productsWithPrices;

    public function __construct($productsWithPrices)
    {
        $this->productsWithPrices = $productsWithPrices;
    }

    public function render()
    {
        return view('components.pricing');
    }
}
