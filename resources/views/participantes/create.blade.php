@extends("plantillas.principal")

@php
    $titulo = "Paso 2: datos del participante";
@endphp

@section("title", $titulo)

@section("description", "Ingrese todos los datos")

@section("seccion")
    <a class="link-fx" href="{{ route('participantes.buscar_participante_por_documento') }}">
        Nueva inscripción
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")
    <form method="post" action="{{ route('participantes.store') }}">
        @csrf        
        @include('participantes._form', ['btnText' => 'Guardar y continuar'])        
    </form>
@endsection