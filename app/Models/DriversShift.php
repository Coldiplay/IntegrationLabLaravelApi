<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DriversShift extends Model
{
    /** @use HasFactory<\Database\Factories\DriversShiftFactory> */
    use HasFactory;


    public function driver() : HasOne
    {
        return $this->hasOne(User::class, 'driver_id');
    }


}
