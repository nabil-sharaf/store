<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrefixesTableSeeder extends Seeder
{
    public function run()
    {
        // إدراج البادئات الخاصة بأنواع المنتجات في جدول prefixes مع وصف بالعربية
        DB::table('prefixes')->insert([
            ['name' => 'T-Shirts', 'prefix_code' => 'TS', 'description' => 'تي شيرتات'],
            ['name' => 'Shoes', 'prefix_code' => 'SH', 'description' => 'أحذية'],
            ['name' => 'Dresses', 'prefix_code' => 'DR', 'description' => 'فساتين'],
            ['name' => 'Toys', 'prefix_code' => 'TY', 'description' => 'ألعاب'],
            ['name' => 'Diapers', 'prefix_code' => 'DP', 'description' => 'حفاضات'],
            ['name' => 'Hats', 'prefix_code' => 'HT', 'description' => 'قبعات'],
            ['name' => 'Bibs', 'prefix_code' => 'BB', 'description' => 'مريلات'],
            ['name' => 'Bottles', 'prefix_code' => 'BT', 'description' => 'زجاجات'],
            ['name' => 'Pants', 'prefix_code' => 'PT', 'description' => 'بناطيل'],
            ['name' => 'Jackets', 'prefix_code' => 'JK', 'description' => 'جواكيت'],
        ]);
    }
}
