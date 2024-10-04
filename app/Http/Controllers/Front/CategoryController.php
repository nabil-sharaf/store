<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use App\Models\Admin\Setting;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){

        $categories = Category::with('products')->get();

        return view('front.categories',compact('categories'));

    }
    public function categoryProducts(Category $category){

        $products = $category->products()->paginate(get_pagination_count());

        return view ('front.category-products',compact('products','category'));

    }

}
