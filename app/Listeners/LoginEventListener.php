<?php

namespace App\Listeners;

use App\Http\Controllers\Front\CartController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LoginEventListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        app(CartController::class)->refreshCartPrices();
    }
}
