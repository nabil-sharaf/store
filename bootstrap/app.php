<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;





return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
       $middleware->alias([
           'admin' => \App\Http\Middleware\Admin\AdminMiddleware::class,
           'guest.admin' => \App\Http\Middleware\Admin\RedirectIfAdminAuthenticated::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
           'checkRole'=>\App\Http\Middleware\Admin\CheckRole::class,
       ]);
        $middleware->web(LaravelLocalizationRoutes::class);
        $middleware->web(LaravelLocalizationRedirectFilter::class);
        $middleware->web(LocaleSessionRedirect::class);
        $middleware->web(LaravelLocalizationViewPath::class);

    })
    ->withProviders([
        // ...
        Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider::class,
    ])

    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
