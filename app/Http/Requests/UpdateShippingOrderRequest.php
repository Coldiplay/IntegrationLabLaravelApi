<?php

namespace App\Http\Requests;

use App\Enums\OrderStatus;
use App\Models\ShippingOrder;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateShippingOrderRequest extends FormRequest
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
            'cargos' => 'sometimes|array',
            'cargos.id' => 'required_if:cargos|exists:cargos,id',
            'receiver_fio' => 'sometimes|string|max:60',
            'receiver_phone' => 'sometimes|string|max:20',
            'receiver_address' => 'sometimes|string|max:60',
            'status' => [
                'sometimes',
                Rule::in(OrderStatus::getKeys()),
            ],
            'shipping_date' => [
                'sometimes',
                Rule::date()->afterOrEqual(now())
            ],
            'sent_date' => [
                'sometimes',
                Rule::date()->afterOrEqual(ShippingOrder::find($this->shipping_order_id)->shipping_date),
            ],
            'received_date' => [
                'sometimes',
                Rule::date()->after(ShippingOrder::find($this->shipping_order_id)->sent_date)
            ]
        ];
    }
}
