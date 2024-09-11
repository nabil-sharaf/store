<?php

return[
   'title'=>'الرئيسية' ,
    'hero_title' => \App\Models\Admin\Setting::getValue('hello_ar') ??'مرحبا بكم في موقعنا',
    'hero_description' => \App\Models\Admin\Setting::getValue('site_disc_ar') ?? 'كل مايخص أطفالك تجده لدينا استمتع  بتجربة رائعة ومختلفة  معنا ',
    'shop_now' => 'تسوق الآن',
    'products' => 'المنتجات',
    'products_description' => 'اطلع على أحدث وأفضل منتجاتنا.',
    'all_products' => 'جميع المنتجات',
    'newly_added' => 'المضاف حديثا',
    'best_sellers' => 'الأكثر مبيعًا',
    'currency'      =>' ج '
];
