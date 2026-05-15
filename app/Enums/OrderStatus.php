<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static InProcessing()
 * @method static static InProgress()
 */
final class OrderStatus extends Enum
{
    const InProcessing = 0;
    const InProgress = 1;


    public function toString(): ?string
    {
        return match ($this->value) {
            self::InProcessing => 'В обработке',
            self::InProgress => 'Ожидает рейса',
            default => null,
        };
    }
}
