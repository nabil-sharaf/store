<?php

use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Admin\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('paginateProducts')) {
    /**
     * Helper function to paginate products.
     *
     * @param \Illuminate\Support\Collection $items
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */

    function paginateProducts($items, $perPage = null)
    {
        $page = request()->get('page', 1);
        $perPage = $perPage ?: config('constants.pagination_count');

        return new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }
}

if (!function_exists('get_pagination_count')) {
    function get_pagination_count()
    {
        return Cache::remember('pagination_count', 3600, function () {
            return Setting::getValue('pagination_count') ?? config('constants.pagination_count'); // القيمة الافتراضية
        });
    }
}

if (!function_exists('get_best_seller_count')) {
    function get_best_seller_count()
    {
        return Cache::remember('best_seller_count', 3600, function () {
            return Setting::getValue('best_seller_count') ?? config('constants.best_seller_count'); // القيمة الافتراضية
        });
    }
}

if (!function_exists('get_trending_count')) {
    function get_trending_count()
    {
        return Cache::remember('trending_count', 3600, function () {
            return Setting::getValue('trending_count') ?? config('constants.trending_count'); // القيمة الافتراضية
        });
    }
}

if (!function_exists('get_last_added_count')) {
    function get_last_added_count()
    {
        return Cache::remember('last_added_count', 3600, function () {
            return Setting::getValue('last_added_count') ?? config('constants.last_added_count'); // القيمة الافتراضية
        });
    }
}
