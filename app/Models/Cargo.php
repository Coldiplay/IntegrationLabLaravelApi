<?php

namespace App\Models;

use App\Casts\DimensionsCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cargo extends Model
{
    /** @use HasFactory<\Database\Factories\CargoFactory> */
    use HasFactory;

    protected $casts = [
        'dimensions' => DimensionsCast::class,
    ];

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
