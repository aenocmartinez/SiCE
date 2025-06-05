<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class GuardarConvenio extends FormRequest
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
        $rules = [
            'nombre' => 'required|max:50',
            'calendario' => 'required',
            'id' => 'numeric|nullable',
            'descuento' => 'required|integer|max:100',
            'esCooperativa' => 'nullable',
            'comentarios' => 'nullable',
        ];

        if ($this->has('esCooperativa')) {
            $rules['reglas'] = 'required|array|min:1';
            $rules['reglas.*.min_participantes'] = 'required|integer|min:1';
            $rules['reglas.*.max_participantes'] = 'required|integer|gt:reglas.*.min_participantes';
            $rules['reglas.*.descuento'] = 'required|numeric|min:0|max:100';
        }

        return $rules;
    }


    public function messages()
    {
        return [
            'calendario.required' => 'El campo periodo es obligatorio',
            'nombre.max' => 'El campo nombre permite máximo 50 caracteres.',
            'reglas.required' => 'Debe agregar al menos una regla de descuento.',
            'reglas.*.min_participantes.required' => 'Debe indicar el mínimo de participantes.',
            'reglas.*.max_participantes.required' => 'Debe indicar el máximo de participantes.',
            'reglas.*.descuento.required' => 'Debe indicar el porcentaje de descuento.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->has('esCooperativa')) {
                $reglas = $this->input('reglas', []);

                // Validar que max > min para cada regla
                foreach ($reglas as $i => $regla) {
                    if (isset($regla['min_participantes'], $regla['max_participantes']) &&
                        $regla['max_participantes'] <= $regla['min_participantes']) {
                        $validator->errors()->add("reglas.$i.max_participantes", 'El máximo debe ser mayor al mínimo.');
                    }
                }

                for ($i = 0; $i < count($reglas); $i++) {
                    for ($j = $i + 1; $j < count($reglas); $j++) {
                        $a = $reglas[$i];
                        $b = $reglas[$j];

                        $a_min = $a['min_participantes'];
                        $a_max = $a['max_participantes'];
                        $b_min = $b['min_participantes'];
                        $b_max = $b['max_participantes'];

                        if (!($a_max < $b_min || $b_max < $a_min)) {
                            $validator->errors()->add("reglas.$i.min_participantes", 'Cruce de rangos con otra regla.');
                            $validator->errors()->add("reglas.$j.min_participantes", 'Cruce de rangos con otra regla.');
                        }
                    }
                }
            }
        });
    }

}
