@extends("plantillas.principal")

@php
    $titulo = "Crear periodo";
@endphp

@section("title", $titulo)

@section("description", "Ingrese todos los datos")

@section("seccion")
    <a class="link-fx" href="{{ route('calendario.index') }}">
        Periodo académico
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")
    <form action="{{ route('calendario.store') }}" method="post">
        @csrf
        @include('calendario._form', ['btnText' => 'Guardar'])
    </form>
@endsection