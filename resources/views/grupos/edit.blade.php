@extends("plantillas.principal")

@php
    $titulo = "Editar grupo";
@endphp

@section("title", $titulo)

@section("description", "Ingrese todos los datos")

@section("seccion")
    <a class="link-fx" href="{{ route('grupos.index') }}">
        Grupos
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")
    <form action="{{ route('grupos.update', [$grupo->getId()]) }}" method="post">
        @csrf @method('patch')
        @include('grupos._form', ['btnText' => 'Actualizar'])
    </form>
@endsection