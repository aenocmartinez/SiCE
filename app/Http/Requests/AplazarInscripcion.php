<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AplazarInscripcion extends FormRequest
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
            'fec_caducidad' => 'required|date_format:Y-m-d',
            'numero_formulario' => 'required',
            'formulario_id' => 'required',
            'calendario_id' => 'required',
            'participante_id' => 'required',
            'saldo_a_favor' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'justificacion.required' => 'Por favor ingrese la justificación.',
            'numero_formulario.required' => 'Por favor ingrese el numero del formulario.',
            'formulario_id.required' => 'Por favor ingrese el formulario_id.',
            'participante_id.required' => 'Por favor ingrese el participante_id.',
            'saldo_a_favor.required' => 'Por favor ingrese el saldo a favor.',
            'calendario_id.required' => 'Por favor ingrese el calendario_id.',            
            'fec_caducidad.required' => 'Por favor ingrese la fecha de caducidad.',
            'fec_caducidad.date_format' => 'La fecha de caducidad debe tener el formato YYYY-mm-dd.',
        ];
    }     
}
