<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentCreateRequest extends FormRequest
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
            'user_id' => 'required|integer',
            'company_id' => 'required|integer',
            'content' => 'required|string|min:150|max:550',
            'rating' => 'required|integer|min:1|max:10',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'Идентификатор пользователя обязателен.',
            'user_id.integer' => 'Идентификатор пользователя должен быть целым числом.',
    
            'company_id.required' => 'Идентификатор компании обязателен.',
            'company_id.integer' => 'Идентификатор компании должен быть целым числом.',
    
            'content.required' => 'Поле контента обязательно для заполнения.',
            'content.string' => 'Поле контента должно быть строкой.',
            'content.min' => 'Поле контента должно содержать минимум :min символов.',
            'content.max' => 'Поле контента должно содержать максимум :max символов.',
    
            'rating.required' => 'Поле рейтинга обязательно для заполнения.',
            'rating.integer' => 'Рейтинг должен быть целым числом.',
            'rating.min' => 'Рейтинг должен быть не менее :min.',
            'rating.max' => 'Рейтинг должен быть не более :max.',
        ];
    }
}
