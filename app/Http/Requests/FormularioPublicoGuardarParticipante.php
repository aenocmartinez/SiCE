<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormularioPublicoGuardarParticipante extends FormRequest
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

    public function rules()
    {
        return [
            'primerNombre' => 'required|max:50',
            'segundoNombre' => 'nullable|max:50',
            'primerApellido' => 'required|max:50',
            'segundoApellido' => 'nullable|max:50',
            'tipoDocumento' => 'required',
            'fecNacimiento' => 'required',
            'direccion' => 'required|max:150',
            'telefono' => 'required|max:30',
            'eps' => 'required',  
            'telefonoEmergencia' => 'required|max:30',
            'documento' => 'required|max:15',
            'sexo' => 'required',
            'estadoCivil' => 'required',
            'email' => 'required|max:250|email',
            'contactoEmergencia' => 'required|max:150',
        ];
    }

    public function messages()
    {
        return [
            'primerNombre.required' => 'Primer nombre es obligatorio',
            'primerNombre.max' => 'Se permiten máximo 50 caracteres',
            'primerApellido.required' => 'Primer apellido es obligatorio',
            'primerApellido.max' => 'Se permiten máximo 50 caracteres', 
            'tipoDocumento.required' => 'Tipo de documento es obligatorio',  
            'fecNacimiento.required' => 'Fecha de nacimiento es obligatorio',
            'direccion.required' => 'Dirección es obligatorio',
            'direccion.max' => 'Se permiten máximo 150 caracteres',
            'telefono.required' => 'Teléfono es obligatorio',
            'telefono.max' => 'Se permiten máximo 30 caracteres',
            'eps.required' => 'EPS es obligatorio',
            'telefonoEmergencia.required' => 'Número de teléfono del contacto de emergencia es obligatorio',
            'telefonoEmergencia.max' => 'Se permiten máximo 30 caracteres',
            'documento.required' => 'Documento es obligatorio',
            'documento.max' => 'Se permiten máximo 15 caracteres',
            'sexo.required' => 'Género es obligatorio',
            'estadoCivil.required' => 'Estado civil es obligatorio',
            'email.required' => 'Correo electrónico es obligatorio',
            'email.max' => 'Se permiten máximo 250 caracteres',
            'email.email' => 'Correo electrónico no válido',
            'contactoEmergencia.required' => 'Nombre del contacto de emergencia es obligatorio',
            'contactoEmergencia.max' => 'Se permiten máximo 150 caracteres',            
        ];
    }       
}
