<?php

namespace App\Providers;

use App\Events\ShippingGotInQueue;
use App\Listeners\NotificationDispatcherListener;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        ShippingGotInQueue::class => [
            NotificationDispatcherListener::class,
        ],
    ];
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
        //
    }
}
