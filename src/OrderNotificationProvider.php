<?php

namespace Quisui\OrderBasicNotification;

use Illuminate\Support\ServiceProvider;

class OrderNotificationProvider extends ServiceProvider
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
        //make the app listen this events and listeners
        $this->app['events']->listen(
            'Quisui\OrderBasicNotification\Events\OrderStatusUpdated',
            'Quisui\OrderBasicNotification\Listeners\SendOrderStatusNotification'
        );
    }
}
