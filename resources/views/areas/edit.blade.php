@extends("plantillas.principal")

@php
    $titulo = "Editar área";
@endphp

@section("title", $titulo)

@section("seccion")
    <a class="link-fx" href="{{ route('areas.index', 1) }}">
        Áreas
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

    <form method="post" action="{{route('areas.update')}}">
        @csrf @method('patch')
        
        <input type="hidden" name="id" value="{{ $area->getId() }}">
        
        @include('areas._form', ['btnText' => 'Actualizar'])

    </form>
    
@endsection