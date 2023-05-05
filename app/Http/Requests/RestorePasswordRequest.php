<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RestorePasswordRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'exists:users'],
            'token' => ['required'],
            'password' => ['required','min:8','confirmed']
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Полето Email е задължително.',
            'password.required' => 'Полето парола е задължително.',
            'password.min' => 'Паролата трябва да има минимум 8 символа.',
            'password.confirmed' => 'Двете пароли трябва да съвпадат.',
            'token.required' => 'Кода за възстановяване на паролата е задължителен.',


        ];
    }
}
