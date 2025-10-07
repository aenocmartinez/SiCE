<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarCorreccionesAsistenciaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'participante_id' => $this->input('participante_id', $this->input('participanteId')),
            'grupo_id'        => $this->input('grupo_id', $this->input('grupoId')),
            'cambios'         => $this->input('cambios', []),
            'observacion'     => $this->input('observacion', $this->input('motivo')),
        ]);
    }

    public function rules(): array
    {
        return [
            'participante_id' => ['required','integer','min:1'],
            'grupo_id'        => ['required','integer','min:1'],
            'cambios'         => ['required','array','min:1'],
            'cambios.*.sesion_id' => ['required','integer','min:1'],
            'cambios.*.asistio'   => ['required','integer','in:0,1'],
            'observacion'     => ['nullable','string','max:500'],
        ];
    }

    public function attributes(): array
    {
        return [
            'participante_id' => 'participante',
            'grupo_id'        => 'grupo',
            'cambios'         => 'cambios',
            'cambios.*.sesion_id' => 'sesión',
            'cambios.*.asistio'   => 'asistencia',
            'observacion'     => 'observación',
        ];
    }
}
