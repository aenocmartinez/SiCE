@extends("plantillas.principal")

@php
    $titulo = "Editar orientador";
@endphp

@section("title", $titulo)

@section("seccion")
    <a class="link-fx" href="{{ route('orientadores.index', 1) }}">
        Orientadores
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

    <form method="post" action="{{route('orientadores.update')}}">
        @csrf @method('patch')
        
        <input type="hidden" name="id" value="{{ $orientador->getId() }}">
        
        @include('orientadores._form', ['btnText' => 'Actualizar'])

    </form>
    
@endsection