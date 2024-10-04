<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use App\Models\Admin\Popup;
use App\Models\Admin\Product;
use App\Models\Admin\Setting;
use App\Models\Admin\SiteImage;
use Illuminate\Http\Request;

class homeController extends Controller
{
    public function index(){

        $categories = Category::with('products')->get();
//        $products = Product::with('images')->get();
        // جلب المنتجات المضافة حديثاً بترتيب تاريخ الإضافة تنازلياً
        $newProducts = Product::orderBy('created_at', 'desc')->take(get_last_added_count())->get();

        // جلب المنتجات المحددة كأكثر مبيعا ً
        $bestProducts = Product::where('is_best_seller',1)->orderBy('updated_at', 'desc')->take(get_best_seller_count())->get();

     // جلب المنتجات المحددة كترند   ً
        $trendingProducts = Product::where('is_trend',1)->orderBy('updated_at', 'desc')->take(get_trending_count())->get();

        $siteImages = SiteImage::first();
        $popup = Popup::first();
        return view('front.index', compact('categories','bestProducts','newProducts','siteImages','trendingProducts','popup'));
    }

    public function productDetails($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json([
            'name' => $product->name,
            'discounted_price' => $product->discounted_price,
            'price' => $product->product_price,
            'description' => $product->description,
            'info' => $product->info,
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
