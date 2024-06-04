@extends("plantillas.principal")

@php
    $titulo = "Detalle inscripción";
@endphp

@section("title", $titulo)

@section("description")
    
@endsection

@section("seccion")
    <a class="link-fx" href="{{ route('participantes.formularios', $formulario->getParticipanteId()) }}">
        Formularios
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

    @include('participantes._detalle_inscripcion', ['btnText' => 'Legalizar inscripción'])        
    
@endsection