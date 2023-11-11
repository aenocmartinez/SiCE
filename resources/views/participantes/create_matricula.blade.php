@extends("plantillas.principal")

@php
    $titulo = "Formulario de inscripción";
    
    $datosParticipante = $participante->getNombreCompleto() . "<br>";
    $datosParticipante .= $participante->getDocumentoCompleto() . "<br>";
    $datosParticipante .= $participante->getEmail() . "<br>";
    $datosParticipante .= $participante->getTelefono();
@endphp

@section("title", $titulo)

@section("description")
    {!! $datosParticipante !!}
@endsection

@section("seccion")
    <a class="link-fx" href="{{ route('participantes.buscar_participante_por_documento') }}">
        Nueva inscripción
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

    <form method="post" action="{{ route('participantes.buscar-grupos') }}">
        @csrf        
        <input type="hidden" name="participante" value="{{ $participante->getId() }}">
        @include('participantes._form_matricula', ['btnText' => 'Guardar y continuar'])        
    </form>
@endsection