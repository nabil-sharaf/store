<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
// تعطيل التحقق من المفاتيح الأجنبية
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('products')->truncate();

// إعادة تمكين التحقق من المفاتيح الأجنبية
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = Category::all();
        foreach ($categories as $category) {
            Product::create([
                'name' => 'Product 1 in ' . $category->name,
                'description' => 'Description for Product 1 in ' . $category->name,
                'quantity' => rand(10, 100),
                'price' => rand(100, 400),
            ]);

            Product::create([
                'name' => 'Product 2 in ' . $category->name,
                'description' => 'Description for Product 2 in ' . $category->name,
                'quantity' => rand(10, 100),
                'price' => rand(100, 1000),
            ]);
        }
    }
}

