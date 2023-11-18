@extends("plantillas.principal")

@php
    $titulo = "Crear participante";
@endphp

@section("title", $titulo)

@section("description", "")

@section("seccion")
    <a class="link-fx" href="{{ route('participantes.index') }}">
        Participantes
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")
    <form action="{{ route('participantes.store') }}" method="post">
        @csrf
        @include('participantes._form', ['btnText' => 'Guardar'])
    </form>
@endsection