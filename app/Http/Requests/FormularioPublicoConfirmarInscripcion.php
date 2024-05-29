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
            'pdf' => 'nullable|file|mimes:pdf|max:2048',
            'estado' => 'nullable',
            'flagComprobante' => 'nullable',
            'formularioId' => 'nullable'
        ];
    }

    /**
     * Customize the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'pdf.required' => 'El comprobante de pago es obligatorio',
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes('pdf', 'required', function ($input) {
            return !is_null($input->flagComprobante) || $input->total_a_pagar > 0;
        });
    }
}
