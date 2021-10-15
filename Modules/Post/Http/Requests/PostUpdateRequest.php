<?php

namespace Modules\Post\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Класс-запрос для выполнения проверки корректности запроса обновления статьи
 *
 * @author Oleg Pyatin
 */
class PostUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title'=>'required|min:2|max:192|string',
            'preview'=>'required|min:20|string',
            'content'=>'required|min:120|string',
            'category'=>'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'title.required'=>'Статья должна иметь название от 2 до 192 символа',
            'title.min'=>'Статья должна иметь название от 2 до 192 символа',
            'preview.required'=>'Превью должно иметь размер минимум 20 символов',
            'preview.min'=>'Превью должно иметь размер минимум 20 символов',
            'content.required'=>'Основной текст должен быть размером минимум 120 символов',
            'content.min'=>'Основной текст должен быть размером минимум 120 символов',
            'category.required'=>'Требуется чтобы была выбрана одна из доступных категорий',
            'category.numeric'=>'Формат категории должен быть верным',
        ];
    }
}
