@extends("plantillas.principal")

@php
    $titulo = "Editar calendario";
@endphp

@section("title", $titulo)

@section("description", "Ingrese todos los datos")

@section("seccion")
    <a class="link-fx" href="{{ route('calendario.index') }}">
        Calendario académico
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")
    <form action="{{ route('calendario.update', [$calendario->getId()]) }}" method="post">
        @csrf @method('patch')
        @include('calendario._form', ['btnText' => 'Actualizar'])
    </form>
@endsection