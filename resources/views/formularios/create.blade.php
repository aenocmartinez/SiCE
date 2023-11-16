@extends("plantillas.principal")

@php
    $titulo = "Paso 2: datos del participante";
@endphp

@section("title", $titulo)

@section("description", "")

@section("seccion")
    <a class="link-fx" href="{{ route('formulario-inscripcion.paso-1') }}">
        Nueva inscripción
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")
    <form method="post" action="{{ route('formulario-inscripcion.store') }}">
        @csrf        
        @include('formularios._form', ['btnText' => 'Guardar y continuar'])        
    </form>
@endsection