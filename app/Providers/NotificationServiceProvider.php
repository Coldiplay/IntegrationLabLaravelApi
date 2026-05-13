<?php

namespace App\Providers;

use App\Channels\SmsChannel;
use App\Contracts\Notifications\Channel as ChannelContract;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ChannelContract::class, function ($app) {
            // For now, we bind the default channel to SMS
            return $app->make(SmsChannel::class);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
