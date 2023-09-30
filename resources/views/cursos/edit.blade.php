@extends("plantillas.principal")

@php
    $titulo = "Editar curso";
@endphp

@section("title", $titulo)

@section("seccion")
    <a class="link-fx" href="{{ route('cursos.index') }}">
        Cursos
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

    <form method="post" action="{{route('cursos.update')}}">
        @csrf @method('patch')
        
        <input type="hidden" name="id" value="{{ $curso['id'] }}">
        
        @include('cursos._form', ['btnText' => 'Actualizar'])

    </form>
    
@endsection