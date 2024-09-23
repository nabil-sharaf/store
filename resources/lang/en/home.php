<?php

return[
    'title'=>'home' ,
    'hero_title' => \App\Models\Admin\Setting::getValue('slider_subject_ar')??'Welcome To Our Website',
    'hero_description' => \App\Models\Admin\Setting::getValue('slider_desc_ar')?? 'The Best place for your Kids Enjoy With Us ' ,
    'shop_now' => 'Shop Now',
    'products' => 'Products',
    'products_description' => 'Check out our latest and best-selling products.',
    'all_products' => 'All Products',
    'newly_added' => 'Newly Added',
    'best_sellers' => 'Best Sellers',
    'currency'      =>' L.E ',
    'deal_of_day_subject'=>'Deal of Day',
    'deal_of_day_desc'=>\App\Models\Admin\Setting::getValue('deal_of_day_desc_ar') ??'خصومات تصل حتى 35%',
    'Trending_products_subject'=>'Trending Products ',
    'Trending_products_desc'=>'' ,
    'shipping_title'=>'Fast shipping and great service',
    'categories' => 'Categories'


];
