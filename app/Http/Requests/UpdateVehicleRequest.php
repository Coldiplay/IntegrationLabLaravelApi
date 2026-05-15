<?php

namespace App\Http\Requests;

use App\Enums\BodyType;
use App\Enums\Rights;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleRequest extends FormRequest
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
        //TODO: При $request->validated() возвращает пустой массив
        return [
            'vehicle_number_plate' => 'sometimes|string|max:15|unique:vehicles,vehicle_number_plate',
            'brand' => 'sometimes|string|max:20',
            'model' => 'sometimes|string|max:40',
            'needed_rights' => ['sometimes', 'integer', 'min:0', Rule::in(Rights::getValues())],
            'lifting_capacity' => ['sometimes', 'numeric', 'min:0'],
            'body_type' => ['sometimes', 'string', Rule::in(BodyType::getKeys())],

            'vehicle_size' => ['sometimes', 'json'],
            'vehicle_size.length' => ['required_with:vehicle_size', 'numeric', 'min:0'],
            'vehicle_size.width' => ['required_with:vehicle_size', 'numeric', 'min:0'],
            'vehicle_size.height' => ['required_with:vehicle_size', 'numeric', 'min:0'],

            'body_size' => ['sometimes', 'json'],
            'body_size.length' => ['required_with:body_size', 'numeric', 'min:0'],
            'body_size.width' => ['required_with:body_size', 'numeric', 'min:0'],
            'body_size.height' => ['required_with:body_size', 'numeric', 'min:0'],

            'max_cargo_volume' => ['sometimes', 'numeric', 'min:0'],
            'vehicle_weight' => ['sometimes', 'numeric', 'min:0'],
            'number_of_axes' => ['sometimes', 'integer', 'min:0', 'max:255'],
        ];
    }
}
