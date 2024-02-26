@extends("plantillas.principal")

@php
    $titulo = "Crear área";    
@endphp

@section("title", $titulo)

@section("description", "Ingrese todos los datos")

@section("seccion")
    <a class="link-fx" href="{{ route('areas.index') }}">
        Áreas
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")
    <form method="post" action="{{ route('areas.store') }}">
        @csrf
        
        @include('areas._form', ['btnText' => 'Guardar'])

        
    </form>
@endsection