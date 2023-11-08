<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarSalon extends FormRequest
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
            'nombre' => 'required|max:10|gt:0',
            'capacidad' => 'required|integer|gt:0|max:2',
            'tipo_salon_id' => 'nullable|integer',
            'disponible' => 'nullable',
            'hoja_vida' => 'nullable'
        ];
    }

    public function messages() {
        return [
            'nombre.required' => 'El campo número es obligatorio',
            'capacidad.integer' => 'El campo capacidad permite únicamente valores positivos.',
            'nombre.max' => 'El campo nombre permite máximo 5 caracteres.',
            'capacidad.max' => 'El campo capacidad permite máximo 2 caracteres.'
        ];
    }
}
