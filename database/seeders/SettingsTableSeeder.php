<?php

namespace Database\Seeders;

use App\Models\Admin\Setting;
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
        Setting::truncate();

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
                'setting_type' => 'string',
                'description' => 'العنوان '
            ],
            [
                'setting_key' => 'about_us',
                'setting_value' => 'ماما ستور لكل ما يخص الأطفال ',
                'setting_type' => 'text',
                'description' => 'من نحن '
            ],
            [
                'setting_key' => 'facebook',
                'setting_value' => 'https://www.facebook.com',
                'setting_type' => 'string',
                'description' => 'facebook'
            ],
            [
                'setting_key' => 'insta',
                'setting_value' => 'https://www.instagram.com/',
                'setting_type' => 'string',
                'description' => 'instagram'
            ],
            [
                'setting_key' => 'whats-app',
                'setting_value' => 'https://wa.me/2010304050',
                'setting_type' => 'string',
                'description' => 'واتساب'
            ],
            [
                'setting_key' => 'shipping_title',
                'setting_value' => 'شحن سريع وإسترجاع مجاني',
                'setting_type' => 'string',
                'description' => 'رسالة الشحن التي تظهر أعلى يمين الهيدر'
            ],
            [
                'setting_key' => 'slider_subject_ar',
                'setting_value' => 'مرحبا بكم في موقعنا',
                'setting_type' => 'string',
                'description' => 'عنوان الرسالة الترحيبية - سلايدر الرئيسة'
            ],
            [
                'setting_key' => 'slider_desc_ar',
                'setting_value' => 'كل ما يخص أطفالك تجده لدينا استمتح بتجربة رائعة ومختلفة معنا',
                'setting_type' => 'string',
                'description' => 'وصف الرسالة الترحيبية - سلايدر الرئيسة'
            ],
            [
                'setting_key' => 'deal_of_day_subject_ar',
                'setting_value' => 'Deal of the day',
                'setting_type' => 'string',
                'description' => 'عنوان عروض deal of the day'
            ],
            [
                'setting_key' => 'deal_of_day_desc_ar',
                'setting_value' => 'خصومات تصل حتى 35 % على ملابس الأطفال',
                'setting_type' => 'text',
                'description' => 'وصف عروض deal of the day'
            ],
            [
                'setting_key' => 'Trending_products_subject',
                'setting_value' => 'Trending Products',
                'setting_type' => 'string',
                'description' => 'عنوان Trending Product'
            ],
            [
                'setting_key' => 'Trending_products_desc',
                'setting_value' => 'المنتجات الأشهر والأكثر إقبالا خلال الفترة الحالية',
                'setting_type' => 'string',
                'description' => 'وصف Trending Product'
            ],
            [
                'setting_key' => 'goomla_min_number',
                'setting_value' => 6,
                'setting_type' => 'integer',
                'description' => 'أقل عدد قطع في الأوردر لعميل الجملة'
            ],
            [
                'setting_key' => 'goomla_min_prices',
                'setting_value' => 3000,
                'setting_type' => 'integer',
                'description' => 'اقل سعر للاوردر لعميل الجملة'
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
