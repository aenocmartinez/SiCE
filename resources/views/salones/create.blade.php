@extends("plantillas.principal")

@php
    $titulo = "Crear salón";
@endphp

@section("title", $titulo)
@section("description", "Ingrese todos los datos")

@section("seccion")
    <a class="link-fx" href="{{ route('salones.index',1) }}">
        Salones
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

    <form method="post" action="{{ route('salones.store') }}">
        @csrf        
        @include('salones._form', ['btnText' => 'Guardar'])
    </form>
@endsection