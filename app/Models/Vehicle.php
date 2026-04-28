<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleFactory> */
    use HasFactory;



    public function shippings() : HasMany
    {
        return $this->hasMany(Shipping::class);
    }

    public function incidents() : HasMany
    {
        return $this->HasMany(Incident::class);
    }
}
