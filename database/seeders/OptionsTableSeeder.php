<?php

namespace Database\Seeders;

use App\Models\Admin\Option;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إيقاف التحقق من المفاتيح الخارجية مؤقتاً
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Option::truncate();
        // إعادة التحقق من المفاتيح الخارجية
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $options = [
            [
                'name' => [
                    'ar' => 'المقاس',
                    'en' => 'dimension',
                ],
                'values' => [
                    ['ar' => 'sm', 'en' => 'sm'],
                    ['ar' => 'lg', 'en' => 'lg'],
                    ['ar' => 'xl', 'en' => 'xl'],
                    ['ar' => '2x', 'en' => '2x'],
                    ['ar' => '3x', 'en' => '3x'],
                    ['ar' => '4x', 'en' => '4x'],
                    ['ar' => '39', 'en' => '39'],
                    ['ar' => '40', 'en' => '40'],
                    ['ar' => '41', 'en' => '41'],
                    ['ar' => '42', 'en' => '42'],
                    ['ar' => '43', 'en' => '43'],
                    ['ar' => '44', 'en' => '44'],
                    ['ar' => '45', 'en' => '45'],
                ],
            ],
            [
                'name' => [
                    'ar' => 'اللون',
                    'en' => 'color',
                ],
                'values' => [
                    // الألوان الأساسية
                    ['ar' => 'أحمر', 'en' => 'red'],
                    ['ar' => 'أزرق', 'en' => 'blue'],
                    ['ar' => 'أخضر', 'en' => 'green'],
                    ['ar' => 'أصفر', 'en' => 'yellow'],
                    ['ar' => 'أسود', 'en' => 'black'],
                    ['ar' => 'أبيض', 'en' => 'white'],

                    // الألوان الفرعية
                    ['ar' => 'وردي', 'en' => 'pink'],
                    ['ar' => 'بنفسجي', 'en' => 'purple'],
                    ['ar' => 'برتقالي', 'en' => 'orange'],
                    ['ar' => 'بني', 'en' => 'brown'],
                    ['ar' => 'رمادي', 'en' => 'gray'],
//                    ['ar' => 'أزرق بحري', 'en' => 'navy'],

                    // درجات الأزرق
//                    ['ar' => 'أزرق فاتح', 'en' => 'lightblue'],
//                    ['ar' => 'أزرق سماوي', 'en' => 'skyblue'],
//                    ['ar' => 'أزرق داكن', 'en' => 'darkblue'],

                    // درجات الأخضر
//                    ['ar' => 'أخضر ليموني', 'en' => 'lime'],
//                    ['ar' => 'أخضر داكن', 'en' => 'darkgreen'],
                    ['ar' => 'زيتوني', 'en' => 'olive'],

                    // درجات الأحمر
//                    ['ar' => 'أحمر داكن', 'en' => 'darkred'],
//                    ['ar' => 'بني محمر', 'en' => 'maroon'],
//                    ['ar' => 'قرمزي', 'en' => 'crimson'],

                    // درجات الرمادي
//                    ['ar' => 'رمادي فاتح', 'en' => 'lightgray'],
                    ['ar' => 'فضي', 'en' => 'silver'],
//                    ['ar' => 'رمادي داكن', 'en' => 'darkgray'],

                    // ألوان إضافية
                    ['ar' => 'بيج', 'en' => 'beige'],
                    ['ar' => 'جملي', 'en' => 'camel'],
                ],
            ],

        ];

        foreach ($options as $option) {
            DB::transaction(function () use ($option) {
                $createdOption = Option::create([
                    'name' => $option['name'], // الحقل المترجم
                ]);

                foreach ($option['values'] as $value) {
                    $createdOption->optionValues()->create([
                        'value' => $value, // الحقل المترجم للقيمة
                    ]);
                }
            });
        }
    }
}
