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
            'nombre' => 'required',
            'capacidad' => 'required|numeric',
            'tipo_salon_id' => 'nullable|numeric',
            'disponible' => 'nullable',
            'hoja_vida' => 'nullable'
        ];
    }

    public function messages() {
        return [
            'nombre.required' => 'El campo número es obligatorio',
        ];
    }
}
