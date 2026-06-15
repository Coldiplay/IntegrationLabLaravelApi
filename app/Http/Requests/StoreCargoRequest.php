<?php

namespace App\Http\Requests;

use App\Enums\DangerLevel;
use App\Enums\Role;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCargoRequest extends FormRequest
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
            'name' => 'required|string|max:40',
            'description' => 'required|string|max:200',
            'weight' => 'required|numeric|min:0',

            'dimensions' => 'required|json',
            'dimensions.weight' => 'required_with:dimensions|numeric|min:0',
            'dimensions.height' => 'required_with:dimensions|numeric|min:0',
            'dimensions.length' => 'required_with:dimensions|numeric|min:0',

            'danger_level' => [
                'required',
                Rule::in(DangerLevel::getKeys())
            ],
            'shipping_order_id' => 'required|exists:shipping_orders,id',
            'shipping_id' => 'sometimes|exists:shippings,id',
        ];
    }
}
