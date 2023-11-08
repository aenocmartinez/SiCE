<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarGrupo extends FormRequest
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
            'curso' => 'required|numeric',
            'salon' => 'required|integer',
            'jornada' => 'required',
            'calendario' => 'required|integer',
            'dia' => 'required',
            'orientador' => 'required|integer',
            'id' => 'numeric|nullable',
        ];
    }

    public function messages()
    {
        return [
            'dia.required' => 'El campo día es obligatorio',
            'salon.required' => 'El campo salón es obligatorio',

        ];
    }
}
