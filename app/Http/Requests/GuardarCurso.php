<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarCurso extends FormRequest
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
            'nombre' => 'required|max:150',
            'area' => 'required',
            'tipoCurso' => 'required',
            'id' => 'numeric|nullable',
        ];
    }

    public function messages()
    {
        return [
            'nombre.max' => 'El campo nombre permite máximo 80 caracteres',
            'area.required' => 'El campo área es obligatorio.',
            'tipoCurso.required' => 'El campo tipo curso es obligatorio.',
        ];
    }
}
