@extends('plantillas.publico')

@section('nameSection', 'Paso 2: Verificación de identidad')

@section('description')
    Para continuar con la descarga del <strong>Certificado de Asistencia</strong>, debe responder correctamente 
    las siguientes preguntas con base en la información registrada en nuestra base de datos.
    <br><br>
    Este paso nos permite asegurarnos de que quien realiza la solicitud es efectivamente el titular del certificado.
@endsection

@section('content')

<div class="col-sm-8 col-xl-6">
    <form method="POST" action="{{ route('certificado.asistencia.descargar') }}">
        @csrf

        @foreach ($preguntas as $index => $pregunta)
            <div class="mb-4">
                <label class="form-label font-weight-bold d-block mb-2 text-center">
                    {{ $pregunta['texto'] }}
                </label>
                <input 
                    type="text" 
                    name="respuesta_{{ $index }}" 
                    required
                    class="form-control py-3 text-center @error("respuesta_{$index}") is-invalid @enderror"
                    placeholder="Ingrese su respuesta">

                {{-- Indicaciones contextuales --}}
                @php
                    $notaAyuda = '';

                    $texto = strtolower($pregunta['texto']);

                    if (str_contains($texto, 'primer nombre') && str_contains($texto, 'primer apellido')) {
                        $notaAyuda = 'Escriba su primer nombre seguido de su primer apellido, separados por un espacio. Ejemplo: Carolina Muñoz';
                    } elseif (str_contains($texto, 'correo') && str_contains($texto, '***@')) {
                        $notaAyuda = 'Escriba su correo electrónico completo. Ejemplo: nombre@dominio.com';
                    } elseif (str_contains($texto, 'mes') && str_contains($texto, 'nació')) {
                        $notaAyuda = 'Ingrese el número del mes. Ejemplo: 03 para marzo.';
                    } elseif (str_contains($texto, 'año') && str_contains($texto, 'nació')) {
                        $notaAyuda = 'Ingrese solo el año en formato numérico. Ejemplo: 1990';
                    }
                @endphp

                @if ($notaAyuda)
                    <small class="form-text text-muted text-center fs-sm">{{ $notaAyuda }}</small>
                @endif

                @error("respuesta_{$index}")
                    <span class="invalid-feedback d-block text-center" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <br>
        @endforeach

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-outline-success" data-toggle="click-ripple">
                <i class="fa fa-check-circle me-1 opacity-50"></i>
                Verificar identidad y descargar certificado
            </button>
        </div>
    </form>
</div>

@endsection
