<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuscarGruposDisponiblesMartricula extends FormRequest
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
            'calendario' => 'required|integer',
            'area' => 'required|integer',
            'participante' => 'required|integer'
        ];
    }

    public function messages()
    {
        return [
            'calendario.required' => 'El campo periodo es obligatorio.',
            'area.required' => 'El campo área es obligatorio.',
            'participante.required' => 'El participante es obligatorio.'
        ];
    }
}
