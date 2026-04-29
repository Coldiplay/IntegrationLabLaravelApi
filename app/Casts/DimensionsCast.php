<?php

namespace App\Casts;

use App\Models\Dimensions;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class DimensionsCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return new Dimensions(
            $attributes['dimensions_width'],
            $attributes['dimensions_length'],
            $attributes['dimensions_height']);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return [
            'dimensions_length' => $value->length,
            'dimensions_width' => $value->width,
            'dimensions_height' => $value->height,
        ];
    }
}
