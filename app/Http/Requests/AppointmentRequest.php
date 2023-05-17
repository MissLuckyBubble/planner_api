<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
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
        $today = now()->format('Y/m/d');
        return [
            'date' => ['required', 'date_format:Y/m/d', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i',
                function ($attribute, $value, $fail) use ($today) {
                    if ($this->date === $today && $value <= now()->format('H:i')) {
                        $fail('Не може да запазите час по-рано то сегашния момент.');
                    }
                }],
            'services' => 'required',
            'services.*' => 'exists:services,id',
            //'status' => ['in:Запазен, Приключен, Отказан, Неизвършен']
        ];
    }

    public function messages()
    {
        return [
            'date.required' => 'Моля изберете дата.',
            'date' => 'Моля изберете валидна дата.',
            'start_time.required' => 'Моля изберете начален час  .',
            'start_time.date_format' => 'Моля изберете валидно време.',
            'services.required' => 'Моля изберете поне една услуга.',
            /*'services.array' => 'Моля изберете валидна услуга.',
            'services.*.exists' => 'Една или повече от избраните услуги не съществува.',*/
        ];
    }
}
