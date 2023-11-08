<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarCalenadario extends FormRequest
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
            'nombre'  => 'required|max:8',
            'fec_ini' => 'required|ae_date_format',
            'fec_fin' => 'required|ae_date_format|after_or_equal:fec_ini',
        ];
    }

    public function messages()
    {
        return [
            'nombre.max' => 'Se permiten máximo 8 caracteres.',
            'fec_ini.required' => 'El campo fecha inicial es obligatorio',
            'fec_fin.required' => 'El campo fecha final es obligatorio',
            'fec_ini.ae_date_format' => 'La fecha inicial debe estar en formato Y-m-d.',
            'fec_fin.ae_date_format' => 'La fecha final debe estar en formato Y-m-d.',
            'fec_fin.after_or_equal' => 'La fecha final debe ser mayor o igual a la fecha inicial.',                
        ];
    }
}
