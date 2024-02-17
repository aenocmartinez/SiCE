@extends("plantillas.principal")

@php
    $titulo = "Áreas orientador";
@endphp

@section("title", $titulo)

@section("seccion")
    <a class="link-fx" href="{{ route('orientadores.index', 1) }}">
        Orientadores
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")
        
@include('orientadores._form_area', ['btnText' => 'Actualizar'])
    
@endsection