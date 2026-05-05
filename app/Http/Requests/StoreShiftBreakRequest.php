<?php

namespace App\Http\Requests;

use App\Models\DriversShift;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreShiftBreakRequest extends FormRequest
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
            'shift_id' => ['required', 'exists:shifts,id'],
            'start' => ['required', Rule::dateTime()->afterOrEqual(DriversShift::find($this->shift_id, 'start')->start)],
            'end' => ['sometimes', Rule::dateTime()->after('start')],
        ];
    }
}
