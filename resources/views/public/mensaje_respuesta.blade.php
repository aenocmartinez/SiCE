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

<div class="col-sm-8 col-xl-8">
    @if ($code == 201)
    <a class="block block-rounded block-link-shadow text-center" href="{{ route('public.descargar-recibo-matricula', $participante) }}">
        <div class="block-content block-content-full">
            <div class="fs-2 fw-semibold text-success">
                <i class="fa fa-check text-success"></i>
            </div>
            <p class="fw-medium fs-sm mb-0">
                <h3>{{ $mensaje }}</h3>                
                <span class="text-info">Haz clic aquí para descargar tu comprobante de matrícula.</span>
                <br><br><br>
                <strong>Inicio de clases a partir del {{ $fec_ini_clase }} en el día y jornada de su curso matriculado.</strong>
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
@endsection