<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|numeric|min:0',
            'service_category_id' => 'nullable|exists:service_categories,id',
        ];

    }

    public function messages()
    {
        return [
            'title.required' => 'Заглавието на услугата е задължително поле.',
            'title.max' => 'Заглавието не може да има повече от 255 символа.',
            'title' => 'Невалидно заглавие.',
            'description' => 'Невалидно описание.',
            'price.required' => 'Цената е задължително поле.',
            'price.numeric' => 'Цената трябва да е числена стойност.',
            'price.min' => 'Цената трябва да е по-голяма от 0,00 лева. ',
            'duration_minutes.required' => 'Полето продължителност е задължително.',
            'duration_minutes.numeric' => 'Полето продължителност трябва да е трябва да е числена стойност.',
            'duration_minutes.min' => 'Стойността на продължителност трябва да е по-голяма от 0 min.',
            'max_capacity.numeric' => 'Полето максимален капацитет трябва да е числена стойност.',
            'max_capacity.min' => 'Стойността на максимален капацитет трябва да е по-голяма.',
            'category_id.exists' => 'Избраната категория не е валидна.',
            'max_capacity.required' => 'Максимален капацитет е задължително поле',
            'start_time.required' => 'Начално време е задължително поле',
            'start_time.date_format' => 'Началното време трябва да е във формат H:i ~ час:минути',
            'date.required' => 'Дата е задължително поле.',
            'date.after_or_equal' => 'Дата не може да е по-рано от днес.',
            'date.date_format' => 'Формата на дата трябва да е Y/m/d',
        ];
    }
}
