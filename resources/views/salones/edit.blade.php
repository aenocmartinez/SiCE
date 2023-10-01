@extends("plantillas.principal")

@php
    $titulo = "Editar salón";
@endphp

@section("title", $titulo)

@section("seccion")
    <a class="link-fx" href="{{ route('salones.index') }}">
        Salones
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

    <form method="post" action="{{route('salones.update')}}">
        @csrf @method('patch')
        
        <input type="hidden" name="id" value="{{ $salon['id'] }}">
        
        @include('salones._form', ['btnText' => 'Actualizar'])

    </form>
    
@endsection