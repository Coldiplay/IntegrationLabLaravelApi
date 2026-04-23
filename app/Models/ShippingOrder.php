<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingOrder extends Model
{
    /** @use HasFactory<\Database\Factories\ShippingOrderFactory> */
    use HasFactory;

    public function cargos() : HasMany
    {
        return $this->hasMany(Cargo::class);
    }
}
