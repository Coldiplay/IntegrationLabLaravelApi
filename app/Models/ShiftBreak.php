<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class ShiftBreak extends Model
{
    /** @use HasFactory<\Database\Factories\ShiftBreakFactory> */
    use HasFactory;

    public function driver() : HasOneThrough
    {
        return $this->hasOneThrough(Driver::class, DriversShift::class,
        'driver_id', 'id');
    }
}
