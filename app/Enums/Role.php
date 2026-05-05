<?php declare(strict_types=1);

namespace App\Enums;

use App\Models\User;
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
    const USER = 1 << 0;
    const ADMIN = 1 << 1;
    const DRIVER = 1 << 2;
    const LOGISTICIAN = 1 << 3;

    // Shortcuts
    const DEFAULT_LOGISTICIAN = self::USER | self::LOGISTICIAN;
    const DEFAULT_DRIVER = self::USER | self::DRIVER;

    public function toString(): ?string
    {
        return match ($this->value) {
            self::USER => 'Пользователь',
            self::ADMIN => 'Администратор',
            self::DRIVER, self::DEFAULT_DRIVER => 'Водитель',
            self::LOGISTICIAN, self::DEFAULT_LOGISTICIAN => 'Логист',
            default => null,
        };
    }

    public static function isAdmin(User $user): bool
    {
        return self::isAdmin($user->role);
    }

    public static function isLogistician(User $user): bool
    {
        return self::isLogistician($user->role);
    }

    public static function isAdminFromValue(int $value): bool
    {
        return self::fromValue($value)->is(Role::Admin());
    }
    public static function isLogisticianFromValue(int $value): bool
    {
        return self::fromValue($value)->is(Role::Logistician());
    }
}
