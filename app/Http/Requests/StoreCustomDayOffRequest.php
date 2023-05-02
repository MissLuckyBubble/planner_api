<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomDayOffRequest extends FormRequest
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
            'date' => [
                'required',
                'date_format:Y/m/d',
                'after:now'
            ]
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Дата е задължително поле.',
            'date_format' => 'Формата трябва да е Y/m/d',
            'after'=> 'Персонализирания почивен ден, не може да е по-рано от днес.'
        ];
    }
}
