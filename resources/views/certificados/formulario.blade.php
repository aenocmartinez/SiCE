@extends('plantillas.publico')

@section('nameSection', 'Paso 1: Verificación de identidad para descargar el certificado')

@section('description')
    Usted está a punto de descargar el <strong>Certificado de Asistencia</strong> a los Cursos de Extensión 
    de la Universidad Mayor de Cundinamarca.
    <br><br>
    Para continuar, por favor ingrese su <strong>tipo</strong> y <strong>número de documento</strong>. 
    A continuación, le haremos unas preguntas de validación que solo usted puede responder.
@endsection

@section('content')

<div class="col-sm-8 col-xl-4">
    @if ($errors->has('error'))
        <div class="alert alert-danger text-center">
            {{ $errors->first('error') }}
        </div>
    @endif

    <form method="post" action="{{ route('certificado.asistencia.verificar') }}">
        @csrf

        <div class="mb-4">
            <select name="tipo_documento" id="tipo_documento" class="form-control py-3 text-center @error('tipo_documento') is-invalid @enderror">
                <option value="">Seleccione tipo de documento</option>
                @foreach($tiposDocumento as $tipo)
                    <option value="{{ $tipo['value'] }}" {{ old('tipo_documento') == $tipo['value'] ? 'selected' : '' }}>
                        {{ $tipo['nombre'] }}
                    </option>
                @endforeach
            </select>
            @error('tipo_documento')
                <span class="invalid-feedback d-block text-center" role="alert">
                    {{ $message }}
                </span>
            @enderror

            <br>

            <input type="text" name="documento" id="documento" class="form-control py-3 text-center @error('documento') is-invalid @enderror" placeholder="Número de documento" value="{{ old('documento') }}">
            @error('documento')
                <span class="invalid-feedback d-block text-center" role="alert">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <!-- Agregar reCAPTCHA aquí -->
        <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY_V2') }}"></div>
        @error('g-recaptcha-response')
            <span class="invalid-feedback d-block" role="alert">
                {{ $message }}
            </span>
        @enderror

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-outline-primary" data-toggle="click-ripple">
                <i class="fa fa-fw fa-magnifying-glass me-1 opacity-50"></i>
                Consultar y continuar
            </button>
        </div>
    </form>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection
