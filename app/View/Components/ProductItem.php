<?php

namespace App\View\Components;

use App\Models\Admin\SiteImage;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductItem extends Component
{
    public $product;

    public function __construct($product)
    {
        $this->product = $product;
    }

    public function render()
    {
        $siteImages = cache()->remember('siteImages', now()->addHours(24), function () {
            return SiteImage::first();
        });
        return view('components.product-item',[
            'siteImages'=>$siteImages,
        ]);
    }
}
