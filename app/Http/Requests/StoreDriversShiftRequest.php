<?php

namespace App\Http\Requests;

use App\Enums\Role;
use App\Enums\ShippingStatus;
use App\Models\Shipping;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDriversShiftRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        return Shipping::where('designated_driver_id', $user->id)
                ->where('shipping_status', ShippingStatus::Shipping()->key) //TODO: Вот не знаю надо ли?
                ->exists()
            || Role::isLogistician($user)
            || Role::isAdmin($user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'start' => ['required', Rule::date()->afterOrEqual(today()->addHours(-2))],
            'end' => 'sometimes|date|after:start',
        ];
    }
}
