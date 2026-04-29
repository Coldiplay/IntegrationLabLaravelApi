<?php

namespace App\Http\Requests;

use App\Enums\Rights;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDriverRequest extends FormRequest
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
            'rights' => [
                'sometimes',
                Rule::in(Rights::getValues()),
            ],
            'drivers_license' => 'sometimes|string|max:40|nullable|unique:drivers,drivers_license',
        ];
    }
}
