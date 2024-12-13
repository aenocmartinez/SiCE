@extends("plantillas.principal")

@php
    $titulo = "Editar usuario";
@endphp

@section("title", $titulo)
@section("description", "Actualice los datos de un usuario")

@section("seccion")
    <a class="link-fx" href="{{ route('users.index') }}">
        Usuarios
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

    <form method="post" action="{{ route('users.update') }}">
        @csrf @method('patch')

        <input type="hidden" name="id" value="{{ $usuario->getId() }}">

        @include('usuarios._form', ['btnText' => 'Guardar'])
    </form>
@endsection