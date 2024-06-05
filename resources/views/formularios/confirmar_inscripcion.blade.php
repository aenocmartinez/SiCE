@extends("plantillas.principal")

@php
    $titulo = "Paso 4: confirmación inscripción";
@endphp

@section("title", $titulo)

@section("description")
    
@endsection

@section("seccion")
    <a class="link-fx" href="{{ route('formulario-inscripcion.paso-1') }}">
        Nueva inscripción
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")
    <form method="post" action="{{ route('formulario-inscripcion.paso-5') }}">
        @csrf        
        <input type="hidden" name="participanteId" value="{{ $participante->getId() }}">
        <input type="hidden" name="grupoId" value="{{ $grupo->getId() }}">
        <input type="hidden" name="convenioId" id="convenioId" value="0">
        <input type="hidden" name="costo_curso" id="costo_curso" value="{{ $grupo->getCosto() }}">
        <input type="hidden" name="valor_descuento" id="valor_descuento" value="0">
        <input type="hidden" name="total_a_pagar" id="total_a_pagar" value="{{ $grupo->getCosto() }}">        
        @include('formularios._form_confirmar_inscripcion', ['btnText' => 'Confirmar inscripcion'])        
    </form>
@endsection