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
            'total_a_pagar' => 'required',
            'valor_descuento' => 'required',
            'convenioId' => 'required',
            'grupoId' => 'required',
            'costo_curso' => 'required',
            'voucher' => 'nullable',
            'valorPago' => 'nullable',
            'medioPago' => 'nullable',
            'pdf' => 'required|file|mimes:pdf|max:2048',
        ];
    }
}
