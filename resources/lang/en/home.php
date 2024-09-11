<?php

return[
    'title'=>'home' ,
    'hero_title' => \App\Models\Admin\Setting::getValue('hello_en')??'Welcome To Our Website',
    'hero_description' => \App\Models\Admin\Setting::getValue('site_disc_en')?? 'The Best place for your Kids Enjoy With Us ' ,
    'shop_now' => 'Shop Now',
    'products' => 'Products',
    'products_description' => 'Check out our latest and best-selling products.',
    'all_products' => 'All Products',
    'newly_added' => 'Newly Added',
    'best_sellers' => 'Best Sellers',
    'currency'      =>' L.E '

];
