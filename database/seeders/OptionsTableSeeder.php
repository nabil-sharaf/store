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

        // ايقاف التحقق من الفورين كي مؤقتا
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Option::truncate();
        // اعادة التحقق من الفورين كي
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $options = [
            [
                'name' => 'Color',
                'values' => ['Red', 'Blue', 'Green', 'Black', 'White', 'Yellow'],
            ],
            [
                'name' => 'Size',
                'values' => ['sm', 'lg', 'xl', 'xxl', '3x', '4x'],
            ],
            [
                'name' => 'Dimension',
                'values' => ['39', '40', '41', '42', '43', '44', '45'],
            ],
        ];
        foreach ($options as $option) {
            DB::transaction(function () use ($option) {
                $createdOption = Option::create(['name' => $option['name']]);
                foreach ($option['values'] as $value) {
                    $createdOption->optionValues()->create(['value' => $value]);
                }
            });
        }
    }
}
