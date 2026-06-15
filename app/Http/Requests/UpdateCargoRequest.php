<?php

namespace App\Http\Requests;

use App\Enums\DangerLevel;
use App\Enums\Role;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCargoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        return Role::isLogistician($user) || Role::isAdmin($user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:40',
            'description' => 'sometimes|string|max:200',
            'weight' => 'sometimes|numeric|min:0',

            'dimensions' => 'sometimes|json',
            'dimensions.weight' => 'required_with:dimensions|numeric|min:0',
            'dimensions.height' => 'required_with:dimensions|numeric|min:0',
            'dimensions.length' => 'required_with:dimensions|numeric|min:0',

            'danger_level' => [
                'sometimes',
                Rule::in(DangerLevel::getKeys())
            ],
            'shipping_order_id' => 'sometimes|exists:shipping_orders,id',
            'cargo_type_id' => 'sometimes|exists:cargo_types,id',
            'shipping_id' => 'sometimes|exists:shippings,id',

        ];
    }
}
