<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductPrice extends Component
{

    public $productPrice;
    public $discountedPrice;

    public function __construct($productPrice, $discountedPrice)
    {
        $this->productPrice = $productPrice;
        $this->discountedPrice = $discountedPrice;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.product-price');
    }
}
