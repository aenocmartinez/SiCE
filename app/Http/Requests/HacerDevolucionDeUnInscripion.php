<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HacerDevolucionDeUnInscripion extends FormRequest
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
            'justificacion' => 'required',
            'numero_formulario' => 'required',
            'formulario_id' => 'required',
            'calendario_id' => 'required',
            'participante_id' => 'required',
            'valor_devolucion' => 'required|numeric|min:1',
            'origen' => 'required',
            'porcentaje' => 'required|numeric|min:1|max:100',
        ];
    }

    public function messages()
    {
        return [
            'justificacion.required' => 'Por favor ingrese la justificación.',
            'numero_formulario.required' => 'Por favor ingrese el numero del formulario.',
            'formulario_id.required' => 'Por favor ingrese el formulario_id.',
            'participante_id.required' => 'Por favor ingrese el participante_id.',
            'valor_devolucion.required' => 'Por favor ingrese el valor de devolución.',
            'valor_devolucion.numeric' => 'El valor de devolución debe ser un número.',
            'valor_devolucion.min' => 'El valor de devolución debe ser mayor a 0.',
            'calendario_id.required' => 'Por favor ingrese el calendario_id.',
            'origen.required' => 'Solicitado por es obligatorio',
            'porcentaje.required' => 'Por favor ingrese el valor de porcentaje.',
            'porcentaje.numeric' => 'El porcentaje debe ser un número.',
            'porcentaje.min' => 'El valor de porcentaje debe ser mayor a 0.',
            'porcentaje.max' => 'El valor de porcentaje debe ser menor a 100.',
        ];
    }
}
