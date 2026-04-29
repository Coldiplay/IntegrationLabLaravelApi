<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Low()
 * @method static static Medium()
 * @method static static High()
 * @method static static Extreme()
 */
final class DangerLevel extends Enum
{
    const Low = 0;
    const Medium = 1;
    const High = 2;
    const Extreme = 3;

    public function toString(): ?string
    {
        return match ($this->value) {
            self::Low => 'Низкий',
            self::Medium => 'Средний',
            self::High => 'Высокий',
            self::Extreme => 'Очень высокий', //??
            default => null,
        };
    }
}
