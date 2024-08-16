<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Order;
use App\Models\Admin\Product;
use App\Models\Admin\OrderDetail;

class OrderDetailsTableSeeder extends Seeder
{
    public function run()
    {
        OrderDetail::truncate();

        $orders = Order::all();
        $products = Product::all();

        foreach ($orders as $order) {
            foreach ($products as $product) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'Product_quantity' => rand(1, 5),
                    'price' => $product->price,
                ]);
            }
        }
    }
}
