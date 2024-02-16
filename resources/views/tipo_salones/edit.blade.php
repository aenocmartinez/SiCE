@extends("plantillas.principal")

@php
    $titulo = "Editar tipo de salón";
@endphp

@section("title", $titulo)

@section("seccion")
    <a class="link-fx" href="{{ route('tipo-salones.index',1) }}">
        Tipo de salón
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

    <form method="post" action="{{route('tipo-salones.update')}}">
        @csrf @method('patch')
        
        <input type="hidden" name="id" value="{{ $tipo->getId() }}">
        
        @include('tipo_salones._form', ['btnText' => 'Actualizar'])

    </form>
    
@endsection