<?php

namespace App\Http\Requests;

use App\Models\ShiftBreak;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateShiftBreakRequest extends FormRequest
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
            'shift_break_id' => 'required|exists:shift_breaks,id',
            'end' => [
                'required',
                Rule::dateTime()->after(ShiftBreak::findOrFail($this->shift_break_id)->start)
            ],
        ];
    }
}
