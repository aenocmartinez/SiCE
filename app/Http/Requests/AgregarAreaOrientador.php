<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgregarAreaOrientador extends FormRequest
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
            'idOrientador' => 'required|integer',
            'area' => 'required|integer'
        ];
    }

    public function messages()
    {
        return [
            'idOrientador.required' => 'El orientador es obligatorio.',
            'area.required' => 'El campo área es obligatorio.'
        ];
    }
}
