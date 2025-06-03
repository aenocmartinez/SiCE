@extends("plantillas.principal")

@php
    $titulo = "Asignación o Cambio de Convenio";
@endphp

@section("title", $titulo)

@section("description")
    
@endsection

@section("seccion")
    <a class="link-fx" href="{{ route('formularios.index') }}">
        Formularios
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

    <form method="post" action="{{ route('formularios.asignar-o-cambiar-convenio') }}">
        @csrf @method('patch')        
        <input type="hidden" name="formularioId" value="{{ $formulario->getId() }}">
        @include('formularios._form_asignar_o_cambiar_convenio', ['btnText' => 'Legalizar inscripción'])        
    </form>
@endsection