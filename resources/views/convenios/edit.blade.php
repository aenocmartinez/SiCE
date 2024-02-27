@extends("plantillas.principal")

@php
    $titulo = "Editar convenio";
@endphp

@section("title", $titulo)

@section("seccion")
    <a class="link-fx" href="{{ route('convenios.index') }}">
        Convenios
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

    <form method="post" action="{{route('convenios.update')}}">
        @csrf @method('patch')
        
        <input type="hidden" name="id" value="{{ $convenio->getId() }}">
        
        @include('convenios._form', ['btnText' => 'Actualizar'])

    </form>
    
@endsection