@extends("plantillas.principal")

@php
    $titulo = "Editar participante";
@endphp

@section("title", $titulo)

@section("seccion")
    <a class="link-fx" href="{{ route('participantes.index') }}">
        Participantes
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

    <form method="post" action="{{route('participantes.update')}}">
        @csrf @method('patch')
        
        <input type="hidden" name="id" value="{{ $participante->getId() }}">
        
        @include('participantes._form', ['btnText' => 'Actualizar'])

    </form>
    
@endsection