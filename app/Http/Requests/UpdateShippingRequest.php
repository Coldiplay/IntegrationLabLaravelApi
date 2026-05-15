<?php

namespace App\Http\Requests;

use App\Enums\ShippingStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateShippingRequest extends FormRequest
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
            'delivery_point' => ['sometimes', 'string', 'max:120'],
            'estimated_delivery_date' => ['sometimes', Rule::dateTime()->after('now')],
            'delivery_date' => ['sometimes', 'date', 'after:shipped_date'],
            'shipping_status' => ['sometimes', Rule::in(ShippingStatus::getKeys())],
            'shipping_date' => ['sometimes', Rule::dateTime()],
            'shipped_date' => ['sometimes', Rule::dateTime()],
            'vehicle_id' => ['sometimes', 'exists:vehicles,id'],
            'designated_driver_id' => ['sometimes', 'exists:drivers,user_id'],
        ];
    }
}
