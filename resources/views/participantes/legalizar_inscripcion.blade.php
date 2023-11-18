@extends("plantillas.principal")

@php
    $titulo = "Legalizacion inscripción";
@endphp

@section("title", $titulo)

@section("description")
    
@endsection

@section("seccion")
    <a class="link-fx" href="{{ route('participantes.index') }}">
        Participantes
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

    <form method="post" action="{{ route('participantes.legalizar-inscripcion') }}">
        @csrf @method('patch')
        <input type="hidden" name="participanteId" value="{{ $formulario->getParticipanteId() }}">
        <input type="hidden" name="formularioId" value="{{ $formulario->getId() }}">
        @include('participantes._form_legalizar_inscripcion', ['btnText' => 'Legalizar inscripción'])        
    </form>
@endsection