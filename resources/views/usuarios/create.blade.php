@extends("plantillas.principal")

@php
    $titulo = "Agregar nuevo usuario";
@endphp

@section("title", $titulo)
@section("description", "Ingrese todos los datos para crear el nuevo usuario")

@section("seccion")
    <a class="link-fx" href="{{ route('users.index') }}">
        Usuarios
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

    <form method="post" action="{{ route('users.store') }}">
        @csrf        
        @include('usuarios._form', ['btnText' => 'Guardar'])
    </form>
@endsection