<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Допускаем, что каждый имеет доступ к этому запросу.
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'name' => 'string|min:3|max:40',
            'description' => 'string|min:150|max:400',
            'logo' => 'image|mimes:png|max:3072',
        ];
    }

    public function messages()
    {
        return [
            'name.string' => 'Поле имя должно быть строкой.',
            'name.min' => 'Поле имя должно содержать минимум :min символов.',
            'name.max' => 'Поле имя должно содержать максимум :max символов.',

            'description.string' => 'Поле описание должно быть строкой.',
            'description.min' => 'Поле описание должно содержать минимум :min символов.',
            'description.max' => 'Поле описание должно содержать максимум :max символов.',

            'logo.mimes' => 'Формат логотипа должен быть PNG.',
            'logo.max' => 'Размер логотипа не должен превышать 3MB.',
        ];
    }
}
