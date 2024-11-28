<?php

namespace App\Providers;

use App\Models\Admin\SiteImage;
use App\Observers\SiteImageObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // تسجيل Observer لنموذج SiteImage
        SiteImage::observe(SiteImageObserver::class);
    }
}
