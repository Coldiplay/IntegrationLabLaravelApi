<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\FlaggedEnum;

/**
 * @method static static User()
 * @method static static Admin()
 * @method static static Driver()
 * @method static static Logistician()
 * @method static static DefaultLogistician()
 * @method static static DefaultDriver()
 * @method static static None()
 */
final class Role extends FlaggedEnum
{
    const User = 1 << 0;
    const Admin = 1 << 1;
    const Driver = 1 << 2;
    const Logistician = 1 << 3;

    // Shortcuts
    const DefaultLogistician = self::User | self::Logistician;
    const DefaultDriver = self::User | self::Driver;

    public function toString(): ?string
    {
        return match ($this->value) {
            self::User => 'Пользователь',
            self::Admin => 'Администратор',
            self::Driver, self::DefaultDriver => 'Водитель',
            self::Logistician, self::DefaultLogistician => 'Логист',
            default => null,
        };
    }

    public static function isAdmin($value): bool
    {
        return self::fromValue($value)->is(Role::Admin());
    }
}
