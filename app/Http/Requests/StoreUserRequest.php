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
        'password' => ['required', 'confirmed', Password::defaults(), Password::min(8)],
        'birth_day' => ['required', 'date_format:Y/m/d', 'before_or_equal:' . now()->subYears(13)->format('Y-m-d')],
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
            'password.min' => 'Паролата трябва да съдържа поне 8 символа.',
            'birth_day.required' => 'Моля въведете, вашата рождена дата.',
            'birth_day.date_format' => 'Рождената дата трябва да е във фолмат Y/m/d.',
            'birth_day.before_or_equal' => 'Трябва да имате поне 13г.',
        ];
    }
}
