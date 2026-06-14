<?php

namespace App\Models;

class Dimensions
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
