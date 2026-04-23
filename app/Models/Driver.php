<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Driver extends Model
{
    /** @use HasFactory<\Database\Factories\DriverFactory> */
    use HasFactory;

    public function user() : ?BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shifts() : HasMany
    {
        return $this->hasMany(DriversShift::class);
    }

//    public function breaks() : HasMany
//    {
//
//    }
}
