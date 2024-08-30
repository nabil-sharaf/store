<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Models\Admin\Setting;
use Illuminate\Http\Request;

class homeController extends Controller
{
    public function index(){

        $categories = Category::with('products')->get();
        $products = Product::with('images')->get();

        return view('front.index', compact('categories','products'));
    }

    public function productDetails($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json([
            'name' => $product->name,
            'price' => $product->price,
            'description' => $product->description,
            'images' => $product->images,
            'categories'=> $product->categories
        ]);
    }

    public function contact()
    {
        $setting = Setting::all();
        return view('front.contact',compact('setting'));
    }

}
