<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class IncidentStatus extends Enum
{
    const Pending = 0;
    const InProgress = 1;
    const Resolved = 2;


    public function toString(): ?string
    {
        return match ($this->value) {
            self::Pending => 'Обрабатывается',
            self::InProgress => 'В процессе',
            self::Resolved => 'Решено',

            default => null
        };
    }
}
