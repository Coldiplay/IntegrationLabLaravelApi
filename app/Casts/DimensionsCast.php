<?php

namespace App\Casts;

use App\Models\Dimensions;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class DimensionsCast implements CastsAttributes
{
    public function __construct(string $prefix)
    {
        $this->prefix = $prefix;
    }

    private string $prefix;

    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return new Dimensions(
            $attributes[$this->prefix . '_width'],
            $attributes[$this->prefix . '_length'],
            $attributes[$this->prefix . '_height']);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return [
            $this->prefix . '_length' => $value->length,
            $this->prefix . '_width' => $value->width,
            $this->prefix . '_height' => $value->height,
        ];
    }
}
