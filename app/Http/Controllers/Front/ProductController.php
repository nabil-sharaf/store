<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function showProduct(Product $product)
    {
        return view('front.product-show');
    }


    public function search(Request $request)
    {
        $query = $request->input('search');

        // البحث في المنتجات
        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->get();

        // عرض النتائج في عرض مخصص
        return view('front.search-results', compact('products', 'query'));
    }

}
