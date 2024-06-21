<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RealizarCambioDeCursoOGrupo extends FormRequest
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
            'numero_formulario' => 'required',
            'area_id' => 'required',
            'justificacion' => 'required',
            'grupoId' => 'required',
            'nuevo_curso' => 'required',
            'accion' => 'required',
            'nuevo_valor_a_pagar' => 'nullable',
            'decision_sobre_pago' => 'nullable'
        ];     
    }

    public function messages()
    {
        return [
            'numero_formulario.required' => 'El campo formulario es obligatorio',
            'justificacion.required' => 'Por favor ingrese la justificación del cambio.',
            'grupoId.required' => 'El campo grupo es obligatorio',   
            'area_id.required' => 'Debe seleccionar el área.',    
            'nuevo_curso.required' => 'Debe seleccionar el grupo',        
        ];
    } 
}
