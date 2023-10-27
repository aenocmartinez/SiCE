@extends("plantillas.principal")

@php
    $titulo = "Crear convenio";    
@endphp

@section("title", $titulo)

@section("description", "Ingrese todos los datos")

@section("seccion")
    <a class="link-fx" href="{{ route('convenios.index') }}">
        Áreas
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")
    <form method="post" action="{{ route('convenios.store') }}">
        @csrf
        
        @include('convenios._form', ['btnText' => 'Guardar'])

        
    </form>
@endsection