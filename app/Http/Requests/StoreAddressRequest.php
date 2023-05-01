<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
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
            'city' => ['required', 'string'],
            'street'=> ['required', 'string'],
            'number' =>['integer'],
            'floor' =>['required','integer'],
            'postal_code' =>['required'],
            'description' =>['max:255'],
        ];
    }

    public function messages()
    {
        return [
            'city.required' => 'Полето град е задължително.',
            'street.required' => 'Полето улица е задължително.',
            'floor.required' => 'Полето етаж е задължително.',
            'postal_code.required' => 'Полето пощенски код е задължително.',
            'description.max' => 'Описанието не може да бъде повече от 255 символа.'
        ];
    }
}
