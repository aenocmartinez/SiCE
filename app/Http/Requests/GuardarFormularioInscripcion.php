<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarFormularioInscripcion extends FormRequest
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
            'primerNombre' => 'required',
            'segundoNombre' => 'nullable',
            'primerApellido' => 'required',
            'segundoApellido' => 'nullable',
            'tipoDocumento' => 'required',
            'documento' => 'required',
            'fecNacimiento' => 'required|date',
            'direccion' => 'required',
            'telefono' => 'required',
            'eps' => 'required',
            'documento' => 'required',
            'sexo' => 'required',
            'estadoCivil' => 'required',
            'email' => 'required|email',
            'contactoEmergencia' => 'required',
            'telefonoEmergencia' => 'required',
            'convenio' => 'nullable|integer',
            'id' => 'nullable'
        ];
    }

    public function messages()
    {
        return [
            'sexo.required' => 'Género es obligatorio',
            'estadoCivil.required' => 'Estado civil es obligatorio',
            'contactoEmergencia.required' => 'El contacto es obligatorio',
            'telefonoEmergencia.required' => 'El teléfono es obligatorio',
            'fecNacimiento.required' => 'Fecha de nacimiento es obligatorio',
            'fecNacimiento.date' => 'Formato de fecha de nacimiento no válido',
            'tipoDocumento.required' => 'Tipo de documento es obligatorio',
            'primerNombre.required' => 'Primer nombre es obligatorio',
            'primerApellido.required' => 'Primer apellido es obligatorio',
        ];
    }    
}
