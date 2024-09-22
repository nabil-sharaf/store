<?php

return[
   'title'=>'الرئيسية' ,
    'hero_title' => \App\Models\Admin\Setting::getValue('slider_subject_ar') ??'مرحبا بكم في موقعنا',
    'hero_description' => \App\Models\Admin\Setting::getValue('slider_desc_ar') ?? 'كل مايخص أطفالك تجده لدينا استمتع  بتجربة رائعة ومختلفة  معنا ',
    'shop_now' => 'تسوق الآن',
    'products' => 'المنتجات',
    'products_description' => 'اطلع على أحدث وأفضل منتجاتنا.',
    'all_products' => 'جميع المنتجات',
    'newly_added' => 'المضاف حديثا',
    'best_sellers' => 'الأكثر مبيعًا',
    'currency'      =>' ج ',
    'deal_of_day_subject'=>\App\Models\Admin\Setting::getValue('deal_of_day_subject_ar') ??'عروض اليوم',
    'deal_of_day_desc'=>\App\Models\Admin\Setting::getValue('deal_of_day_desc_ar') ??'خصومات تصل حتى 50 %',
    'Trending_products_subject'=>\App\Models\Admin\Setting::getValue('Trending_products_subject') ??'Trending Products ',
    'Trending_products_desc'=>\App\Models\Admin\Setting::getValue('Trending_products_desc_ar') ?? '',
    'shipping_title'=>\App\Models\Admin\Setting::getValue('shipping_title')
];
