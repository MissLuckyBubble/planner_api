<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
        'phoneNumber' => ['required', 'string', 'size:9', 'unique:users'],
        'email' => ['required', 'email:rfc,dns', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed', Password::defaults(), Password::min(8)]
            ];
    }

    public function messages()
    {
        return [
            'phoneNumber.required' => 'Полето телефонен номер е задължително.',
            'email.required' => 'Полето email е задължително.',
            'password.required' => 'Полето парола е задължително.',
            'phoneNumber' => 'Невалиден или същестуващ телефонен номер.',
            'email' => 'Невалиден или същестуващ email.',
            'password.confirmed' => 'Паролите трябва да съвпадат.',
            'password.min' => 'Паролата трябва да съдържа поне 8 символа.' ,
        ];
    }
}
