<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreBusinessRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'phoneNumber' => ['required', 'string', 'size:9', 'unique:users'],
            'email' => ['required', 'email:rfc,dns', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults(), Password::min(8)],
            'phoneNumber' => ['required', 'string', 'max:9', 'min:9', 'unique:users'],
            'name' => ['required','max:255', 'unique:businesses'],
            'eik' => ['required', 'unique:businesses', 'regex:/^\d{13}$|^\d{9}$/' ]
        ];
    }
}
