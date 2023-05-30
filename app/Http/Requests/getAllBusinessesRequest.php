<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class getAllBusinessesRequest extends FormRequest
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
            'search' => ['sometimes',],
            'category_id' => ['sometimes', 'exists:categories',],
            'city'=>['sometimes'],
            'rating' => ['sometimes', 'between:1-5',],
            'page'=>['sometimes','integer'],
            'per_page'=>['sometimes','integer'],
            'sortBy' => ['sometimes','in:Име,Рейтинг','nullable'],
            'sortOrder' => ['sometimes','in:asc,desc', 'nullable'],
        ];
    }
}
