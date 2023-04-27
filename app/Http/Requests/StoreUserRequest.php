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
}
