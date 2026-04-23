<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Incident extends Model
{
    /** @use HasFactory<\Database\Factories\IncidentFactory> */
    use HasFactory;

    public function driver() : BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function shipping() : BelongsTo
    {
        return $this->belongsTo(User::class, 'shipping_id');
    }
}
