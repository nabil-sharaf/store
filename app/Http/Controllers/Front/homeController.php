<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Models\Admin\Setting;
use App\Models\Admin\SiteImage;
use Illuminate\Http\Request;

class homeController extends Controller
{
    public function index(){

        $categories = Category::with('products')->get();
        $products = Product::with('images')->get();
        // جلب المنتجات المضافة حديثاً بترتيب تاريخ الإضافة تنازلياً
        $newProducts = Product::orderBy('created_at', 'desc')->take(4)->get();

        // جلب المنتجات الأكثر مبيعاً بترتيب الكمية المباعة تنازلياً
        $bestProducts = Product::where('is_best_seller',1)->orderBy('id', 'desc')->take(4)->get();

        $siteImages = SiteImage::first();
        return view('front.index', compact('categories','products','bestProducts','newProducts','siteImages'));
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

    public function aboutUs()
    {
        $siteImages = SiteImage::first();
        return view('front.about',compact('siteImages'));
    }

}
