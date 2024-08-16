<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use Illuminate\Support\Facades\DB;

class CategoryProductTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('category_product')->truncate();

        $categories = Category::all();
        $products = Product::all();

        foreach ($categories as $category) {
            foreach ($products as $product) {
                $category->products()->attach($product->id);
            }
        }
    }
}
