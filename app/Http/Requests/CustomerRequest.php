<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'name' => ['max:255'],
            'birth_day' => ['required', 'date_format:Y/m/d', 'before_or_equal:' . now()->subYears(13)->format('Y-m-d')],
            'sex' => ['in:мъж,жена,друго'],
        ];
    }

    public function messages()
    {
        return [
            'birth_day.required' => 'Моля въведете, вашата рождена дата.',
            'birth_day.date_format' => 'Рождената дата трябва да е във фолмат Y/m/d.',
            'birth_day.before_or_equal' => 'Трябва да имате поне 13г.',
            'sex' => 'Невалидни данни.',
            'name' => 'Името може да съдържа най-много 255 символа.'
            ];
    }
}
