<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrefixesTableSeeder extends Seeder
{
    public function run()
    {
        // إدراج البادئات الخاصة بأنواع المنتجات في جدول prefixes مع اسم بريفكس باللغتين العربية والإنجليزية
        DB::table('prefixes')->insert([
            [
                'name' => json_encode([
                    'en' => 'T-Shirts',
                    'ar' => 'تي شيرتات',
                ]),
                'prefix_code' => 'TS',
            ],
            [
                'name' => json_encode([
                    'en' => 'Shoes',
                    'ar' => 'أحذية',
                ]),
                'prefix_code' => 'SH',
            ],
            [
                'name' => json_encode([
                    'en' => 'Dresses',
                    'ar' => 'فساتين',
                ]),
                'prefix_code' => 'DR',
            ],
            [
                'name' => json_encode([
                    'en' => 'Toys',
                    'ar' => 'ألعاب',
                ]),
                'prefix_code' => 'TY',
            ],
            [
                'name' => json_encode([
                    'en' => 'Diapers',
                    'ar' => 'حفاضات',
                ]),
                'prefix_code' => 'DP',
            ],
            [
                'name' => json_encode([
                    'en' => 'Hats',
                    'ar' => 'قبعات',
                ]),
                'prefix_code' => 'HT',
            ],
            [
                'name' => json_encode([
                    'en' => 'Bibs',
                    'ar' => 'مريلات',
                ]),
                'prefix_code' => 'BB',
            ],
            [
                'name' => json_encode([
                    'en' => 'Bottles',
                    'ar' => 'زجاجات',
                ]),
                'prefix_code' => 'BT',
            ],
            [
                'name' => json_encode([
                    'en' => 'Pants',
                    'ar' => 'بناطيل',
                ]),
                'prefix_code' => 'PT',
            ],
            [
                'name' => json_encode([
                    'en' => 'Jackets',
                    'ar' => 'جواكيت',
                ]),
                'prefix_code' => 'JK',
            ],
        ]);
    }
}
