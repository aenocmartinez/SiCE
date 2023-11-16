@extends("plantillas.principal")

@php
    $titulo = "Paso 3: selecciona el curso";
    
    $datosParticipante = $participante->getNombreCompleto() . "<br>";
    $datosParticipante .= $participante->getDocumentoCompleto() . "<br>";
    $datosParticipante .= $participante->getEmail() . "<br>";
    $datosParticipante .= $participante->getTelefono();
@endphp

@section("title", $titulo)

@section("description", "")

@section("seccion")
    <a class="link-fx" href="{{ route('formulario-inscripcion.paso-1') }}">
        Nueva inscripción
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

    <form method="post" action="{{ route('formulario-inscripcion.paso-3.buscar-grupos') }}">
        @csrf        
        <input type="hidden" name="participante" value="{{ $participante->getId() }}">
        @include('formularios._form_select_grupo_inscripcion', ['btnText' => 'Guardar y continuar'])        
    </form>
@endsection