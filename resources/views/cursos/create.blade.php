@extends("plantillas.principal")

@php
    $titulo = "Crear curso";
@endphp

@section("title", $titulo)

@section("description", "Ingrese todos los datos")

@section("seccion")
    <a class="link-fx" href="{{ route('cursos.index','1') }}">
        Cursos
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")
    <form action="{{ route('cursos.store') }}" method="post">
        @csrf
        @include('cursos._form', ['btnText' => 'Guardar'])
    </form>
@endsection