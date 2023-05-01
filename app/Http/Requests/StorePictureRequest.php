<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePictureRequest extends FormRequest
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
            'file' => ['required' , 'mimes:jpeg,png,jpg', 'max:2048']
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'Полето е задължително.',
            'file.mimes' => 'Позволени файлове: jpeg,png,jpg.',
            'file.max' => 'Максимален размер: 2048.',
        ];
    }
}
