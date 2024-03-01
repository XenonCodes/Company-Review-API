<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
            'first_name' => 'required|string|min:3|max:40',
            'last_name' => 'required|string|min:3|max:40',
            'phone_number' => 'required|string|regex:/^\+7\d{10}$/',
            'avatar' => 'image|mimes:jpg,png|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'Поле "Имя" обязательно для заполнения.',
            'first_name.string' => 'Поле "Имя" должно быть строкой.',
            'first_name.min' => 'Поле "Имя" должно содержать минимум :min символов.',
            'first_name.max' => 'Поле "Имя" должно содержать максимум :max символов.',
    
            'last_name.required' => 'Поле "Фамилия" обязательно для заполнения.',
            'last_name.string' => 'Поле "Фамилия" должно быть строкой.',
            'last_name.min' => 'Поле "Фамилия" должно содержать минимум :min символов.',
            'last_name.max' => 'Поле "Фамилия" должно содержать максимум :max символов.',
    
            'phone_number.required' => 'Поле "Номер телефона" обязательно для заполнения.',
            'phone_number.string' => 'Поле "Номер телефона" должно быть строкой.',
            'phone_number.regex' => 'Неверный формат номера телефона. Пожалуйста, используйте формат +7XXXXXXXXXX.',
    
            'avatar.image' => 'Файл должен быть изображением.',
            'avatar.mimes' => 'Формат изображения должен быть JPG или PNG.',
            'avatar.max' => 'Размер изображения не должен превышать 2MB.',
        ];
    }
}
