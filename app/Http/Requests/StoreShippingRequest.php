<?php

namespace App\Http\Requests;

use App\Enums\ShippingStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreShippingRequest extends FormRequest
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
            'delivery_point' => ['required', 'string', 'max:120'],
            'estimated_delivery_date' => ['required', Rule::dateTime()->after('now')],
            'shipping_status' => ['sometimes', Rule::in(ShippingStatus::getKeys())],
            'shipping_date' => ['required', Rule::dateTime()],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'designated_driver_id' => ['required', 'exists:drivers,user_id'],
        ];
    }
}
