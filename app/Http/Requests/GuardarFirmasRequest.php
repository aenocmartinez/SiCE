<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Src\domain\Firma;
use Src\usecase\certificados\ObtenerFirmasUseCase;

class GuardarFirmasRequest extends FormRequest
{
    private Firma $firmaActual;

    public function prepareForValidation(): void
    {
        $this->firmaActual = app(ObtenerFirmasUseCase::class)->ejecutar()->data;
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $firma = $this->firmaActual;

        return [
            'nombre_firmante1' => 'required|string|max:255',
            'cargo_firmante1' => 'required|string|max:255',
            'ruta_firma1' => [
                $firma->getRutaFirma1() ? 'nullable' : 'required',
                'file',
                'mimes:jpg,jpeg,png',
                'max:1024'
            ],

            'nombre_firmante2' => 'required|string|max:255',
            'cargo_firmante2' => 'required|string|max:255',
            'ruta_firma2' => [
                $firma->getRutaFirma2() ? 'nullable' : 'required',
                'file',
                'mimes:jpg,jpeg,png',
                'max:1024'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Este campo es obligatorio.',
            'mimes' => 'Solo se permiten archivos JPG o PNG.',
            'max' => 'El archivo no debe superar 1 MB.',
        ];
    }
}
