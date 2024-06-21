@extends("plantillas.principal")

@php
    $titulo = "Iniciar un nuevo trámite";    
@endphp

@section("title", $titulo)

@section("description", "En este formulario realice un cambio, un traslado, un aplazamiento o una cancelación.")

@section("seccion")
    <a class="link-fx" href="{{ route('cambios-traslados.index') }}">
        Volver al listado
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")
    <form method="post" action="{{ route('areas.store') }}">
        @csrf
        
        @include('cambios-traslados._form')
        
    </form>
@endsection