<?php

namespace App\Observers;

use App\Jobs\PublishModelEvent;
use App\Models\Shipping;

class ShippingObserver
{
    public function created(Shipping $shipping): void {
        PublishModelEvent::dispatch(Shipping::class, $shipping->id, 'created');
    }

    public function updated(Shipping $shipping): void {
        PublishModelEvent::dispatch(Shipping::class, $shipping->id, 'updated');
    }

    public function deleted(Shipping $shipping): void {
        PublishModelEvent::dispatch(Shipping::class, $shipping->id, 'deleted');
    }
}
