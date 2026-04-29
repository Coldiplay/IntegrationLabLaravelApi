<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class BodyType extends Enum
{
    const Awning = 0;
    const Van = 1;
    const Isothermal = 2;
    const Refrigerator = 3;
    const OnboardPlatform = 4;
    const DumpTruck = 5;
    const Tank = 6;
    const ContainerShip = 7;

    public function toString() : ?string
    {
        return match ($this) {
            self::Awning => 'Тент',
            self::Van => 'Фургон',
            self::Isothermal => 'Изотермический',
            self::Refrigerator => 'Рефрижератор',
            self::OnboardPlatform => 'Бортовая платформа',
            self::DumpTruck => 'Самосвал',
            self::Tank => 'Цистерна',
            self::ContainerShip => 'Контейнеровоз',
            default => null,
        };
    }
}
