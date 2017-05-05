<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookImportFormRequests extends FormRequest
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
            'fileCSV' => 'required|mimes:csv,txt'
        ];
    }

    public function messages()
    {
        return [
            'fileCSV.required' => 'É obrigatório selecionar um arquivo para realizar a importação!'
            , 'fileCSV.mimes' => 'O arquivo deve possuir uma das seguintes extenções: csv, txt!'
        ];
    }
}
