@extends("plantillas.principal")

@php
    $titulo = "Mi Perfil";
@endphp

@section("title", $titulo)
@section("description", "Actualice sus datos personales")

@section("seccion")
    <a class="link-fx" href="{{ route('dashboard') }}">
        Dashboad
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

    <form method="post" action="{{ route('users.update_profile') }}">
        @csrf @method('patch')

        <input type="hidden" name="id" value="{{ $usuario->getId() }}">

        @include('usuarios._profile', ['btnText' => 'Guardar'])
    </form>
@endsection