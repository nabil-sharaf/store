<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Admin\Category;
use Illuminate\Http\Request;

class homeController extends Controller
{
    public function index(){

        $categories = Category::with('products')->get();

        return view('front.layouts.app', compact('categories'));
    }
}
