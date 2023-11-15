<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuscarParticipantePorDocumento extends FormRequest
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
            'documento' => 'required',
            'tipoDocumento' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'tipoDocumento.required' => 'Tipo de documento es obligatorio'
        ];
    }
}
