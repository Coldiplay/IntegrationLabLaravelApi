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
        //TEST
        //$test = Vehicle::factory(1)->create()->first();
        //$array = $test->supportedCargoTypes()->get();

        //Не зависят от других factory
        User::factory(15)->create();
        User::factory()->create([
            'login' => 'admin',
            'email' => 'admin@example.com',
            'role' => Role::ADMIN,
            'password' => 'password'
        ]);

        User::factory()->create([
            'login' => 'driver',
            'email' => 'driver@example.com',
            'password' => 'password',
            'role' => Role::DEFAULT_DRIVER
        ]);

        ShippingOrder::factory(15)->create();
        CargoType::factory(15)->create();
        Chat::factory(15)->create();
        Vehicle::factory(15)->create();
        //

        //TEST
        $cargoTypes = CargoType::all()->pluck('id')->toArray();
        $vehicles = Vehicle::all();
        $vehicleId = null;
        $cargoTypeId = null;
        foreach ($vehicles as $vehicle) {
            $blockedIds = $vehicle->supportedCargoTypes()->pluck('id')->toArray();
            foreach ($cargoTypes as $cargoType) {
                if (!in_array($cargoType, $blockedIds)) {
                    $vehicleId = $vehicle->id;
                    $cargoTypeId = $cargoType;
                    break;
                }
            }
        }

        if (empty($vehicleId)) {
            $vehicleId = Vehicle::factory(1)->create()->first()->id;
            $cargoTypeId = CargoType::factory(1)->create()->first()->id;
        }
        try {
            TransportCargoType::create(['vehicle_id' => $vehicleId, 'cargo_type_id' => $cargoTypeId]);
        }
        catch (\Exception $exception) {
            throw new \Exception('vehicleId ' . $vehicleId . ', cargoTypeId ' . $cargoTypeId);
        }

        //TESTEND


        //TransportCargoType::factory(15)->create();

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
