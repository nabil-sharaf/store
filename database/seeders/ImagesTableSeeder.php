<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Image;
use App\Models\Admin\Product;

class ImagesTableSeeder extends Seeder
{
    public function run()
    {
        Image::truncate();

        $products = Product::all();

        foreach ($products as $product) {
            Image::create([
                'name' => $product->name . ' Image 1',
                'path' => 'products/66c0719f68780.jpg',
                'product_id' => $product->id,
            ]);

            Image::create([
                'name' => $product->name . ' Image 2',
                'path' => 'products/66c070bfec2e7.jpg',
                'product_id' => $product->id,
            ]);
        }
    }
}
