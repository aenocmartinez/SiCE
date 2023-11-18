<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmarInscription extends FormRequest
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
            'medioPago' => 'required',
            'participanteId' => 'required|integer',
            'grupoId' => 'required|integer',
            'convenioId' => 'required|nullable',
            'costo_curso' => 'required',
            'valor_descuento' => 'required',
            'total_a_pagar' => 'required',
            'voucher' => 'required_if:medioPago,pagoDatafono',
        ];
    }

    public function messages()
    {
        return [
            'medioPago.required' => 'Por favor indicar el medio de pago.',
            'voucher.required_if' => 'El campo voucher es obligatorio'
        ];
    }
}
