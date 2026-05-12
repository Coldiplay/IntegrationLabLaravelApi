<?php

namespace App\Models;

use App\Casts\DimensionsCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Vehicle extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleFactory> */
    use HasFactory;

    protected $casts = [
        'vehicle_size' => DimensionsCast::class . ':vehicle_size',
        'body_size' => DimensionsCast::class . ':body_size',
    ];

    protected $guarded = [];


    public function supportedCargoTypes() : HasManyThrough
    {
        return $this->hasManyThrough(CargoType::class, TransportCargoType::class,
            secondKey: 'id',
            secondLocalKey: 'cargo_type_id');
    }

    public function shippings() : HasMany
    {
        return $this->hasMany(Shipping::class);
    }

    public function incidents() : HasMany
    {
        return $this->HasMany(Incident::class);
    }
}
