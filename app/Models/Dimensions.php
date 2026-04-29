<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dimensions extends Model
{
    public function __construct(float $width, float $length, float $height)
    {
        $this->width = $width;
        $this->height = $height;
        $this->length = $length;
    }

    public float $length;
    public float $width;
    public float $height;
}
