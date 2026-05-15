<?php

namespace App\Http\Requests;

use App\Enums\BodyType;
use App\Enums\Rights;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVehicleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vehicle_number_plate' => 'required|string|unique:vehicles,vehicle_number_plate',
            'brand' => 'required|string|max:20',
            'model' => 'required|string|max:40',
            'needed_rights' => ['required', 'integer', 'min:0', Rule::in(Rights::getValues())],
            'lifting_capacity' => ['required', 'numeric', 'min:0'],
            'body_type' => ['required', 'string', Rule::in(BodyType::getKeys())],

            'vehicle_size' => ['required', 'json'],
            'vehicle_size.length' => ['required', 'numeric', 'min:0'],
            'vehicle_size.width' => ['required', 'numeric', 'min:0'],
            'vehicle_size.height' => ['required', 'numeric', 'min:0'],

            'body_size' => ['required', 'json'],
            'body_size.length' => ['required', 'numeric', 'min:0'],
            'body_size.width' => ['required', 'numeric', 'min:0'],
            'body_size.height' => ['required', 'numeric', 'min:0'],

            'max_cargo_volume' => ['required', 'numeric', 'min:0'],
            'vehicle_weight' => ['required', 'numeric', 'min:0'],
            'number_of_axes' => ['required', 'integer', 'min:0', 'max:255'],
        ];
    }
}
