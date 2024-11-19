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
                    'ar' => 'اللون',
                    'en' => 'color',
                ],
                'values' => [
                    ['ar' => 'أحمر', 'en' => 'red'],
                    ['ar' => 'أزرق', 'en' => 'blue'],
                    ['ar' => 'أخضر', 'en' => 'green'],
                    ['ar' => 'أسمر', 'en' => 'black'],
                    ['ar' => 'أبيض', 'en' => 'white'],
                    ['ar' => 'أصفر', 'en' => 'yellow'],
                ],
            ],
            [
                'name' => [
                    'ar' => 'الحجم',
                    'en' => 'dimension',
                ],
                'values' => [
                    ['ar' => 'sm', 'en' => 'sm'],
                    ['ar' => 'lg', 'en' => 'lg'],
                    ['ar' => 'xl', 'en' => 'xl'],
                    ['ar' => '2x', 'en' => '2x'],
                    ['ar' => '3x', 'en' => '3x'],
                    ['ar' => '4x', 'en' => '4x'],
                ],
            ],
            [
                'name' => [
                    'ar' => 'المقاس',
                    'en' => 'length',
                ],
                'values' => [
                    ['ar' => '39', 'en' => '39'],
                    ['ar' => '40', 'en' => '40'],
                    ['ar' => '41', 'en' => '41'],
                    ['ar' => '42', 'en' => '42'],
                    ['ar' => '43', 'en' => '43'],
                    ['ar' => '44', 'en' => '44'],
                    ['ar' => '45', 'en' => '45'],
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
