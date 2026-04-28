<?php

namespace App\Http\Requests;

use App\Models\ChatMember;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return ChatMember::where('chat_id', $this->chat_id)
            ->where('user_id', $this->user()->id)
            ->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:255', 'min:1'],
            'chat_id' => ['required', 'exists:chats,id'],
        ];
    }
}
