<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransportCargoType extends Model
{
    /** @use HasFactory<\Database\Factories\TransportCargoTypeFactory> */
    use HasFactory;

    public function vehicle() : BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function cargoType() : BelongsTo
    {
        return $this->belongsTo(CargoType::class);
    }
}
