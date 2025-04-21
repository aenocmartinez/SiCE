<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarOrientador extends FormRequest
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
            'id' => 'nullable',
            'nombre' => 'required',
            'tipoDocumento' => 'required',
            'documento' => 'required',
            'emailInstitucional' => 'required|regex:/^.+@unicolmayor\.edu\.co$/i',
            'emailPersonal' => 'nullable|email',
            'direccion' => 'nullable',
            'eps' => 'nullable',
            'area' => 'nullable',
            'observacion' => 'nullable',
            'fecNacimiento' => 'nullable|date',
            'nivelEstudio' => 'nullable',
            'areas' => 'required|array',
            'rangoSalarial' => 'nullable'
        ];
    }

    public function messages() {
        return [
            'emailInstitucional.required' => 'Correo electrónico institucional obligatorio.',
            'emailInstitucional.regex' => 'Sólo se permiten email terminados en @unicolmayor.edu.co',
            'fecNacimiento.date' => 'Formato no válido para el campo fecha de nacimiento.',
            'areas.required' => 'El campo áreas a las que pertenece es obligatorio.',
            'emailInstitucional.email' => 'Correo electrónico no válido.',            
        ];
    }
}
