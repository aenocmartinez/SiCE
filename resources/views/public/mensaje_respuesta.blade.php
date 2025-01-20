@extends('plantillas.publico')

@section('nameSection', '¡Hemos finalizado!')

@section('description')

@endsection

@section('content')
<style>
.captcha-img {
    width: 200px; /* Se ajusta el tamaño de la imagen */
    height: auto;
    font-size: 20px; /* Se ajusta el tamaño de la fuente del texto */
}
</style>

@if (session()->has('SESSION_UUID'))

<div class="col-sm-8 col-xl-8">
    @if ($code == 201)
    <a id="download-link" class="block block-rounded block-link-shadow text-center" href="{{ route('public.descargar-recibo-matricula', $participante) }}">
        <div class="block-content block-content-full">
            <div class="fs-2 fw-semibold text-success">
                <i class="fa fa-check text-success"></i>
            </div>
            <p class="fw-medium fs-sm mb-0">
                <h3>{{ $mensaje }}</h3>                
                <span class="text-info">Haz clic aquí para descargar tu comprobante de matrícula.</span>
                <br><br><br>
                <strong>
                    Se informa que el pago de su curso(s) ha sido legalizado, e igualmente, el inicio de clases es a partir del {{ $fec_ini_clase }}, en el día y jornada de su curso matriculado.
                </strong>
            </p>            
        </div>
    </a>
    @else
    <div class="block-content block-content-full text-center">
            <div class="fs-2 fw-semibold text-danger">
                <i class="fa fa-times text-danger"></i>
            </div>
            <p class="fw-medium fs-sm mb-0">
                <h3 class="text-danger">{{ $mensaje }}</h3>
                <br>
                <a href="{{ route('public.inicio') }}" class="text-primary-darker">Haz clic aquí para regresar al comienzo</a>
            </p>            
        </div>    
    @endif
    
</div>

@else
    Su sesión ha finalizado
@endif  

<script type="text/javascript">
    document.getElementById('download-link').addEventListener('click', function(event) {
        event.preventDefault(); // Prevenir la navegación por defecto

        // Abrir el enlace de descarga en una nueva ventana
        var downloadUrl = this.href;
        var newWindow = window.open(downloadUrl, '_blank');

        // Cerrar la ventana actual después de un breve retraso
        setTimeout(function() {
            window.close();
        }, 1000); // Ajusta el tiempo según sea necesario
    });
</script>

@endsection
