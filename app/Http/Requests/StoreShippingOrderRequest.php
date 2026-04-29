<?php

namespace App\Http\Requests;

use App\Enums\OrderStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreShippingOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cargos' => 'required|array|min:1',
            'cargos.id' => 'required_if:cargos|exists:cargos,id',
            //'order_date' => 'required|date',
            'receiver_fio' => 'required|string|max:60',
            'receiver_phone' => 'required|string|max:20',
            'address' => 'required|string|max:120',
            'status' => [
                'sometimes',
                Rule::in(OrderStatus::getKeys()),
            ],
            'shipping_date' => [
                'required',
                Rule::date()->afterOrEqual(now())
            ],
            'sent_date' => [
                'sometimes',
                'required_if:shipping_date',
                Rule::date()->afterOrEqual('shipping_date')
            ],
            'received_date' => [
                'sometimes',
                'required_if:sent_date',
                Rule::date()->after('sent_date')
            ]
        ];
    }
}
