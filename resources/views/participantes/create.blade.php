@extends("plantillas.principal")

@php
    $titulo = "Formulario de inscripción";    
@endphp

@section("title", $titulo)

@section("description", "Ingrese todos los datos")

@section("seccion")
    <a class="link-fx" href="{{ route('participantes.buscar_participante_por_documento') }}">
        Nuevo formulario
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")
    <form method="post" action="{{ route('participantes.store') }}">
        @csrf        
        @include('participantes._form', ['btnText' => 'Guardar formulario'])        
    </form>
@endsection