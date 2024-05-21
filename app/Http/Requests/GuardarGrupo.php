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
            'capacidad_salon' => 'required|integer',
            'cupo' => 'required|integer|gt:0|digits_between:1,2',
            'id' => 'numeric|nullable',
            'bloqueado' => 'nullable'
        ];
    }

    public function messages()
    {
        return [
            'dia.required' => 'El campo día es obligatorio',
            'salon.required' => 'El campo salón es obligatorio',
            'cupo.required' => 'El campo cupos es obligatorio',
            'cupo.integer' => 'El campo cupos permite únicamente valores positivos.',
            'cupo.digits_between' => 'El campo cupo permite máximo 2 caracteres.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $cantidad1 = $this->input('capacidad_salon');
            $cantidad2 = $this->input('cupo');

            if ($cantidad2 > $cantidad1) {
                $validator->errors()->add('cupo', 'El cupo no puede superar la capacidad del salón.');
            }
        });
    }    
}
