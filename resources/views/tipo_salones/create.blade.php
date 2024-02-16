@extends("plantillas.principal")

@php
    $titulo = "Crear tipo de salón";
@endphp

@section("title", $titulo)
@section("description", "Ingrese todos los datos")

@section("seccion")
    <a class="link-fx" href="{{ route('tipo-salones.index',1) }}">
        Tipo de salón
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

    <form method="post" action="{{ route('tipo-salones.store') }}">
        @csrf        
        @include('tipo_salones._form', ['btnText' => 'Guardar'])
    </form>
@endsection