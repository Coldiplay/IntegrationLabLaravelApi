<?php declare(strict_types=1);

namespace App\Enums;

use App\Models\User;
use BenSampo\Enum\FlaggedEnum;

/**
 * @method static static USER()
 * @method static static ADMIN()
 * @method static static DRIVER()
 * @method static static LOGISTICIAN()
 * @method static static DEFAULT_LOGISTICIAN()
 * @method static static DEFAULT_DRIVER()
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

    public static function isAdmin(User|int $user): bool
    {
        return self::isCheck($user, Role::ADMIN);
    }

    public static function isLogistician(User|int $user): bool
    {
        return self::isCheck($user, Role::LOGISTICIAN);
    }

    public static function isDriver(User|int $user): bool
    {
        return self::isCheck($user, Role::DRIVER);
    }

    private static function checkFlag(int $value, int $flag): bool
    {
        return Role::fromValue($value)->hasFlag($flag);
    }
    private static function isCheck($user, int $flag): bool
    {
        return match (gettype($user)) {
            'integer' => self::checkFlag(User::find($user)->role, $flag),
            'object' => self::checkFlag($user->role, $flag),
            default => false,
        };
    }
}
