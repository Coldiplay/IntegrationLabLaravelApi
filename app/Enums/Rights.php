<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\FlaggedEnum;

/**
 * @method static static A()
 * @method static static B()
 * @method static static AandB()
 * @method static static None()
 */
final class Rights extends FlaggedEnum
{
    const A = 1 << 0;
    const B = 1 << 1;

    // Shortcuts
    const AandB = self::A | self::B;
}
