@extends('plantillas.publico')

@section('nameSection', 'Paso 1: Consulta si ya estás en nuestra base de datos')

@section('description')
    Usted está a punto de comenzar el proceso de inscripción a los 
    <strong>Cursos de Extensión</strong> de la Universidad Colegio Mayor de Cundinamarca.
    <br><br>
    Le invitamos a que ingrese su número de documento y compruebe si se encuentra registrado. 
    En caso que no lo esté se mostrará un formulario vacío en donde podrá registrar sus datos.
@endsection

@section('content')

<div class="col-sm-8 col-xl-4">
    <form method="post" action="{{ route('public.consultar-existencia') }}">
        @csrf
        <div class="mb-4">
            <select name="tipoDocumento" id="tipoDocumento" class="form-control py-3 text-center @error('tipoDocumento') is-invalid @enderror">
                <option value="CC" {{ old('tipoDocumento') == "CC" ? 'selected' : '' }} selected>Cédula de ciudadanía</option>
                <option value="TI" {{ old('tipoDocumento') == "TI" ? 'selected' : '' }}>Tarjeta de identidad</option>
                <option value="CE" {{ old('tipoDocumento') == "CE" ? 'selected' : '' }}>Cédula de extranjería</option>
                <option value="PP" {{ old('tipoDocumento') == "PP" ? 'selected' : '' }}>Pasaporte</option>
            </select>
            @error('tipoDocumento')
                <span class="invalid-feedback" role="alert">
                    {{ $message }}
                </span>
            @enderror
            <br>
            <input type="text" class="form-control py-3 text-center @error('documento') is-invalid @enderror" autocomplete="off" id="documento" name="documento" placeholder="Número de documento" value="{{ old('documento') }}">
            @error('documento')
                <span class="invalid-feedback text-center" role="alert">
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
                Continuar
            </button>
        </div>
    </form>
</div>

<!-- Modal -->
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="infoModalLabel">Información importante</h5>
      </div>
      <div class="modal-body text-center">
        {!! $mensajeVentanaModal !!}
      </div>
    </div>
  </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = new bootstrap.Modal(document.getElementById('infoModal'));
        modal.show();

        setTimeout(() => {
            modal.hide();
        }, 6000); 
    });
</script>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection
