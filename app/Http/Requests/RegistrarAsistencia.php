<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrarAsistencia extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para la solicitud de asistencia.
     */
    public function rules(): array
    {
        return [
            'grupo_id' => ['required', 'integer', 'min:1'],
            // 'sesion'   => ['required', 'integer', 'between:1,16'],
            'sesion'   => ['required', 'integer'],
            'asistencias' => ['required', 'array', 'min:1'],
            'asistencias.*.participante_id' => ['required', 'integer', 'min:1'],
            'asistencias.*.presente'        => ['required', 'boolean'],
        ];
    }

    /**
     * Mensajes personalizados (opcional).
     */
    public function messages(): array
    {
        return [
            'grupo_id.required' => 'El grupo es obligatorio.',
            'sesion.required'   => 'La sesión es obligatoria.',
            // 'sesion.between'    => 'La sesión debe estar entre 1 y 16.',
            'asistencias.required' => 'Debe registrar al menos una asistencia.',
            'asistencias.*.participante_id.required' => 'El ID del participante es obligatorio.',
            'asistencias.*.presente.required' => 'Debe indicar si el participante estuvo presente.',
            'asistencias.*.presente.boolean'  => 'El valor de asistencia debe ser verdadero o falso.',
        ];
    }
}
