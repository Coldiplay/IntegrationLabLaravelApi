<?php

namespace App\Http\Requests;

use App\Enums\IncidentStatus;
use App\Models\Shipping;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreIncidentRequest extends FormRequest
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
            'shipping_id' => 'required|exists:shippings,id',
            'driver_id' => 'required|exists:drivers,user_id',
            'description' => 'sometimes|string|max:500|nullable',
            'incident_date' => ['required', Rule::date()->after(Shipping::find($this->shipping_id)->shipped_date)],
            'status' => ['sometimes', Rule::in(IncidentStatus::getKeys())],
        ];
    }
}
