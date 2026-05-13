<?php

namespace App\Listeners;

use App\Events\ShippingGotInQueue;
use App\Http\Resources\ShippingResource;
use App\Models\Driver;
use App\Services\Notifications\NotificationOrchestrator;

class NotificationDispatcherListener
{
    public function __construct(private NotificationOrchestrator $orchestrator) {}

    public function handle(ShippingGotInQueue $event): void
    {
        $this->orchestrator->notifySms(
            phone: Driver::find($event->driverId)->user->phone,
            templateKey: 'shipping_got_in_queue',
            data: ['shipping' => new ShippingResource($event->shipping)],
            locale: $event->locale
        );
    }
}
