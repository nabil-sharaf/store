<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            [
                'setting_key' => 'site_name',
                'setting_value' => 'mama store',
                'setting_type' => 'string',
                'description' => 'اسم الموقع'
            ],
            [
                'setting_key' => 'email',
                'setting_value' => 'admin@mamastore.com',
                'setting_type' => 'string',
                'description' => 'البريد الالكتروني'
            ],
            [
                'setting_key' => 'phone',
                'setting_value' => '01010000000',
                'setting_type' => 'string',
                'description' => 'رقم التواصل'
            ],
            [
                'setting_key' => 'address',
                'setting_value' => 'مدينة نصر القاهرة',
                'setting_type' => 'text',
                'description' => 'العنوان '
            ],
            [
                'setting_key' => 'about_us',
                'setting_value' => 'ماما ستور لكل ما يخص الأطفال ',
                'setting_type' => 'text',
                'description' => 'من نحن '
            ],
            [
                'setting_key' => 'Maintenance_mode',
                'setting_value' => 0,
                'setting_type' => 'integer',
                'description' => 'وضع الصيانة'
            ],

        ]);
    }
}
