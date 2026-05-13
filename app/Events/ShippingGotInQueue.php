<?php

namespace App\Events;

use App\Models\Shipping;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ShippingGotInQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $driverId,
        public string $phone,
        public Shipping $shipping,
        public string $locale = 'ru'
    ) {}
}
