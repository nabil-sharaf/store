<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\ShippingRate;

class ShippingRateSeeder extends Seeder
{
    public function run()
    {
        $states = [
            ['state' => 'القاهرة', 'shipping_cost' => 50.00],
            ['state' => 'الجيزة', 'shipping_cost' => 50.00],
            ['state' => 'الإسكندرية', 'shipping_cost' => 60.00],
            ['state' => 'الدقهلية', 'shipping_cost' => 70.00],
            ['state' => 'البحر الأحمر', 'shipping_cost' => 90.00],
            ['state' => 'البحيرة', 'shipping_cost' => 65.00],
            ['state' => 'الفيوم', 'shipping_cost' => 55.00],
            ['state' => 'الغربية', 'shipping_cost' => 60.00],
            ['state' => 'الإسماعيلية', 'shipping_cost' => 60.00],
            ['state' => 'المنوفية', 'shipping_cost' => 65.00],
            ['state' => 'المنيا', 'shipping_cost' => 75.00],
            ['state' => 'القليوبية', 'shipping_cost' => 55.00],
            ['state' => 'الوادي الجديد', 'shipping_cost' => 80.00],
            ['state' => 'السويس', 'shipping_cost' => 65.00],
            ['state' => 'اسوان', 'shipping_cost' => 100.00],
            ['state' => 'اسيوط', 'shipping_cost' => 85.00],
            ['state' => 'بني سويف', 'shipping_cost' => 60.00],
            ['state' => 'بورسعيد', 'shipping_cost' => 55.00],
            ['state' => 'دمياط', 'shipping_cost' => 60.00],
            ['state' => 'الشرقية', 'shipping_cost' => 65.00],
            ['state' => 'جنوب سيناء', 'shipping_cost' => 90.00],
            ['state' => 'كفر الشيخ', 'shipping_cost' => 65.00],
            ['state' => 'مطروح', 'shipping_cost' => 75.00],
            ['state' => 'الأقصر', 'shipping_cost' => 95.00],
            ['state' => 'قنا', 'shipping_cost' => 90.00],
            ['state' => 'شمال سيناء', 'shipping_cost' => 85.00],
            ['state' => 'سوهاج', 'shipping_cost' => 95.00],
            ['state' => 'شرم الشيخ', 'shipping_cost' => 85.00],
            ['state' => 'الغردقة', 'shipping_cost' => 90.00],
        ];

        foreach ($states as $state) {
            ShippingRate::create($state);
        }
    }
}
