@extends("plantillas.principal")

@php
    $titulo = "Crear grupo";
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
    <form action="{{ route('grupos.store') }}" method="post">
        @csrf
        @include('grupos._form', ['btnText' => 'Guardar'])
    </form>
@endsection