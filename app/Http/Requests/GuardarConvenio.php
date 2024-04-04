<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarConvenio extends FormRequest
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
            'nombre' => 'required|max:50',
            'calendario' => 'required',
            'fec_ini' => 'required|ae_date_format',
            'fec_fin' => 'required|ae_date_format',            
            'id' => 'numeric|nullable',
            'descuento' => 'required|integer|max:100',
            'esCooperativa' => 'nullable'
        ];
    }

    public function messages()
    {
        return [
            'calendario.required' => 'El campo periodo es obligatorio',
            'nombre.max' => 'El campo nombre permite máximo 50 caracteres.',
            'fec_ini.required' => 'El campo fecha inicial es obligatorio.',
            'fec_fin.required' => 'El campo fecha final es obligatorio.',
        ];
    }
}
