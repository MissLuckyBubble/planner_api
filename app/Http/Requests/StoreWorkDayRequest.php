<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkDayRequest extends FormRequest
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
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'pause_start' => 'nullable|date_format:H:i|before:end_time',
            'pause_end' => 'nullable|date_format:H:i|after:pause_start|before:end_time',
        ];

    }

    public function messages()
    {
        return [

            'required' => 'Старт и Край на работния ден са задължителни полета.',
            'date_format' => 'Часовете трябва да са във формат: H:i ~ час:минути',
            'end_time.after' => 'Края трябва да е след старта на работния ден.',
            'pause_start.before' => 'Старта на почивката трябва да е преди края на работния ден.',
            'pause_end.after' => 'Края на почивката трябва да е след началото й.',
            'pause_end.before' => 'Края на почивката трябва да е преди края на работния ден.',

        ];
    }
}
