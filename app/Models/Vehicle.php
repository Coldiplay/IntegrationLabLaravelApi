<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Vehicle extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleFactory> */
    use HasFactory;



    public function shippings() : HasMany
    {
        return $this->hasMany(Shipping::class);
    }

    //TODO: Придумать, как связать с происшествиями
//    public function incidents() : HasManyThrough
//    {
//        return $this->HasManyThrough(Incident::class, Shipping::class);
//    }
}
