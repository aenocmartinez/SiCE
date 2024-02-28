<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LegalizarFormularioInscripcion extends FormRequest
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
            'formularioId' => 'required|integer',
            'participanteId' => 'required|integer',
            'voucher' => 'required',
            'valorPago' => 'required',
            'valor_pendiente_por_pagar' => 'required',
            'convenioId' => 'nullable',
            'valor_descuento' => 'nullable',
            'costo_curso' => 'required',
            'total_a_pagar' => 'required',
        ];                
    }

    public function messages()
    {
        return [
            'valorPago.required' => 'El campo valor a pagar es obligatorio',
        ];
    } 
    
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $cantidad1 = $this->input('valor_pendiente_por_pagar');
            $cantidad2 = $this->input('valorPago');

            if ($cantidad2 != $cantidad1) {
                $validator->errors()->add('valorPago', 'El valor a pagar debe ser igual el valor total a pagar.');
            }
        });
    }      
}
