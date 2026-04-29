<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ShippingStatus extends Enum
{
    const InProcessing = 0;
    const ReadyToShip = 1;
    const Shipping = 2;
    const Delivered = 3;
    const Incident = 4;


    public function toString(): ?string
    {
        return match ($this->value) {
            self::InProcessing => 'В обработке',
            self::ReadyToShip => 'Готово к отправке',
            self::Shipping => 'В пути',
            self::Delivered => 'Доставлено',
            self::Incident => 'Происшествие',
            default => null
        };
    }

}
