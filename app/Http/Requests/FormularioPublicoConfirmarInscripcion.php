<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormularioPublicoConfirmarInscripcion extends FormRequest
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
            'participanteId' => 'required',
            'total_a_pagar' => 'required|numeric',
            'valor_descuento' => 'required',
            'convenioId' => 'required',
            'grupoId' => 'required',
            'costo_curso' => 'required',
            'voucher' => 'nullable',
            'valorPago' => 'nullable',
            'medioPago' => 'nullable',
            // 'pdf' => 'required_if|file|mimes:pdf|max:2048',
            'pdf' => 'nullable|file|mimes:pdf|max:2048',
            'estado' => 'nullable',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Verifica si total_a_pagar es mayor a 0
            if ($this->total_a_pagar > 0) {
                // Si total_a_pagar es mayor a 0, entonces el archivo PDF es requerido
                $validator->sometimes('pdf', 'required', function ($input) {
                    return $input->total_a_pagar > 0;
                });
            }
        });
    }
}
