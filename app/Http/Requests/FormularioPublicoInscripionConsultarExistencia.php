<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormularioPublicoInscripionConsultarExistencia extends FormRequest
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
            'tipoDocumento' => 'required',
            'documento' => 'required',
            // 'captcha' => 'required|captcha',
        ];
    }

    public function messages()
    {
        return [
            // 'captcha.required' => 'Captcha es obligatorio',
            // 'captcha.captcha' => 'Captcha no válido',         
        ];
    }  
}
