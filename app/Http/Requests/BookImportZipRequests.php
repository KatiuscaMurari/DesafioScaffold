<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookImportZipRequests extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'fileZIP' => 'required|mimes:zip'
        ];
    }

    public function messages() {
        return [
            'fileZIP.required' => 'É obrigatório selecionar um arquivo para realizar a importação!'
            , 'fileZIP.mimes' => 'O arquivo deve possuir uma das seguintes extenções: zip!'
        ];
    }

}
