<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categoryProducts(Category $category){

        $products = $category->products()->get();
        $title = $category->name;
        return view ('front.category-products',compact('products','title'));

    }
}