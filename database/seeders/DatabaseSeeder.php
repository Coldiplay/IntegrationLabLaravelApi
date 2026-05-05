<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Cargo;
use App\Models\CargoType;
use App\Models\Chat;
use App\Models\ChatMember;
use App\Models\Driver;
use App\Models\DriversShift;
use App\Models\Incident;
use App\Models\Message;
use App\Models\ShiftBreak;
use App\Models\Shipping;
use App\Models\ShippingOrder;
use App\Models\TransportCargoType;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Не зависят от других factory
        User::factory(15)->create();
        User::factory()->create([
            'login' => 'admin',
            'email' => 'admin@example.com',
            'role' => Role::ADMIN,
            'password' => 'password'
        ]);

        ShippingOrder::factory(15)->create();
        CargoType::factory(15)->create();
        Chat::factory(15)->create();
        Vehicle::factory(15)->create();
        //

        TransportCargoType::factory(15)->create();

        $driversCount = User::where('role', Role::DEFAULT_DRIVER)
            ->orWhere('role', Role::DRIVER)
            ->count();

        if ($driversCount <= 1)
        {
            User::factory(3)->create([
                'role' => Role::DEFAULT_DRIVER,
            ]);
            $driversCount = $driversCount + 3;
        }

        Driver::factory($driversCount)->create();
        DriversShift::factory(60)->create();
        ShiftBreak::factory(120)->create();

        ChatMember::factory(15)->create();
        Message::factory(200)->create();

        Shipping::factory(7)->create();
        Incident::factory(4)->create();
        Cargo::factory(40)->create();
    }
}
