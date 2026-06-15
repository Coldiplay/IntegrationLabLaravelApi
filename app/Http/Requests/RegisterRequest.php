<?php

namespace App\Http\Requests;

use App\Enums\Role;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        return Role::isAdmin($user)
            || Role::isLogistician($user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => 'required|string|max:32|unique:users',
            'first_name' => 'required|string|max:40',
            'last_name' => 'required|string|max:40',
            'patronymic' => 'sometimes|string|max:40',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|max:64|email|unique:users',
            'password' => 'required|string|confirmed',
            'hire_date' => 'sometimes|date|before:tomorrow',
            'role' => [
                'sometimes',
                'integer',
                Rule::in(Role::getValues())
            ]
        ];
    }
}
