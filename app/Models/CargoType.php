<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class CargoType extends Model
{
    /** @use HasFactory<\Database\Factories\CargoTypeFactory> */
    use HasFactory;

    public function cargos() : HasMany
    {
        return $this->hasMany(Cargo::class);
    }

    public function supportedVehicles() : HasManyThrough
    {
        return $this->hasManyThrough(Vehicle::class, TransportCargoType::class,
        'id',
        'vehicle_id');
    }
}
