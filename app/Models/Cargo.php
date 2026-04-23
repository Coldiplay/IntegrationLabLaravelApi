<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cargo extends Model
{
    /** @use HasFactory<\Database\Factories\CargoFactory> */
    use HasFactory;



    public function shippingOrder() : BelongsTo
    {
        return $this->belongsTo(ShippingOrder::class);
    }

    public function cargoType() : BelongsTo
    {
        return $this->belongsTo(CargoType::class);
    }

    public function shipping() : ?BelongsTo
    {
        return $this->belongsTo(Shipping::class);
    }
}
