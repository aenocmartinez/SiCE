@extends("plantillas.principal")

@php
    $titulo = "Detalle inscripción";
@endphp

@section("title", $titulo)

@section("description")
    
@endsection

@section("seccion")
    <a class="link-fx" href="{{ route('formularios.index') }}">
        Formularios
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

    @include('formularios._detalle_inscripcion', ['btnText' => 'Legalizar inscripción'])        
    
@endsection