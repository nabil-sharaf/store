<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
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


    }
}
