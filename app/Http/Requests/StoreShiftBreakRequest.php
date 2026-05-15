<?php

namespace App\Http\Requests;

use App\Enums\Role;
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
        return empty(DriversShift::find($this->shift_id, 'end')->end)
            || Role::isLogistician($this->user())
            || Role::isAdmin($this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $shift = DriversShift::find($this->shift_id, 'start');
        return [
            'shift_id' => ['required', 'exists:shifts,id'],
            'start' => ['required', Rule::dateTime()->afterOrEqual($shift->start)],
            'end' => ['sometimes', Rule::dateTime()->after('start')],
        ];
    }
}
