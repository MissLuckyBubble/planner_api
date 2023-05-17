<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetAppointmentsRequest extends FormRequest
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
            'date' => ['sometimes', 'date_format:Y/m/d',],
            'date_before' => ['sometimes', 'date_format:Y/m/d',],
            'date_after' => ['sometimes', 'date_format:Y/m/d',],
            'status' => ['sometimes','in:Запазен,Приключен,Отказан,Неизвършен'],
            'sortBy' => ['sometimes','in:Цена,Дата,Продължителност,Статус'],
            'sortOrder' => ['sometimes','in:Възходящо,Низходящо'],
        ];
    }
}
