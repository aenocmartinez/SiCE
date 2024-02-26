@extends("plantillas.principal")

@php
    $titulo = "Crear orientador";
@endphp

@section("title", $titulo)

@section("seccion")
    <a class="link-fx" href="{{ route('orientadores.index') }}">
        Orientadores
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

    <form method="post" action="{{ route('orientadores.store') }}">
        @csrf
        
        @include('orientadores._form', ['btnText' => 'Guardar'])

    </form>
@endsection