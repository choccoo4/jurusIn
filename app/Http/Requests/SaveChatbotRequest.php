<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveChatbotRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'session_id' => ['required', 'string', 'max:255'],
            'chat_data' => ['required', 'string', 'max:10000'],
        ];
    }

    public function messages(): array
    {
        return [
            'session_id.required' => 'missing session id.',
            'chat_data.required' => 'missing chat data.',
            'chat_data.max' => 'Chat to long.',
        ];
    }
}
