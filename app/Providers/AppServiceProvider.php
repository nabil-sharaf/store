<?php

namespace App\Providers;

use App\Models\Admin\SiteImage;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Event::listen(
            \Illuminate\Auth\Events\Login::class,
            \App\Listeners\LoginEventListener::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // تحقق من وجود جدول site_images
        if (Schema::hasTable('site_images')) {
            try {
                $siteImages = cache()->remember('siteImages', now()->addHours(24), function () {
                    return SiteImage::first();
                });

                // تحقق من أن $siteImages ليس null قبل المشاركة
                if ($siteImages) {
                    View::share('siteImages', $siteImages);
                } else {
                    View::share('siteImages', null);
                }
            } catch (\Exception $e) {
                View::share('siteImages', null);
            }
        }
    }
}
