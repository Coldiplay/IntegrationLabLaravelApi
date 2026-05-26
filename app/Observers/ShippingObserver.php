<?php

namespace App\Observers;

use App\Jobs\ShippingUpdated;
use App\Models\Shipping;

class ShippingObserver
{
    /**
     * Handle the Shipping "created" event.
     */
    public function created(Shipping $shipping): void
    {
        dispatch(new ShippingUpdated($shipping->id))
            ->onConnection('rabbitmq')
            ->onQueue('shippings-updates-queue');
    }

    /**
     * Handle the Shipping "updated" event.
     */
    public function updated(Shipping $shipping): void
    {
        dispatch(new ShippingUpdated($shipping->id))
            ->onConnection('rabbitmq')
            ->onQueue('shippings-updates-queue');
    }

    /**
     * Handle the Shipping "deleted" event.
     */
    public function deleted(Shipping $shipping): void
    {
        //
    }

    /**
     * Handle the Shipping "restored" event.
     */
    public function restored(Shipping $shipping): void
    {
        //
    }

    /**
     * Handle the Shipping "force deleted" event.
     */
    public function forceDeleted(Shipping $shipping): void
    {
        //
    }
}
