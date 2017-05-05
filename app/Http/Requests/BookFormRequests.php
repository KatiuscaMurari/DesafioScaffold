<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookFormRequests extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:300|min:5'
            , 'authors' => 'required|max:100|min:5'
            , 'publisher' => 'max:100'
            , 'image' => 'mimes:jpeg,bmp,png,jpg'
            , 'description' => 'max:5000'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'O campo título é de preenchimento obrigatório!'
            , 'title.min' => 'O campo título deve ter no mínimo 5 caracteres!'
            , 'title.max' => 'O campo título deve ter no máximo 300 caracteres!'
            , 'authors.required' => 'O campo autor é de preenchimento obrigatório!'
            , 'authors.min' => 'O campo autor deve ter no mínimo 5 caracteres!'
            , 'authors.max' => 'O campo autor deve ter no máximo 100 caracteres!'
            , 'publisher.max' => 'O campo editora deve ter no máximo 100 caracteres!'
            , 'description.max' => 'O campo descrição deve ter no máximo 5000 caracteres!'
            , 'image.mimes' => 'A imagem deve só pode ter as seguintes extensões: png, jpg, jpge, gif!'
        ];
    }
}
