@extends("plantillas.principal")

@php
    $titulo = "Legalizacion inscripción";
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

    <form method="post" action="{{ route('formularios.legalizar-inscripcion') }}">
        @csrf @method('patch')
        <input type="hidden" name="participanteId" value="{{ $formulario->getParticipanteId() }}">
        <input type="hidden" name="formularioId" value="{{ $formulario->getId() }}">

        <input type="hidden" name="grupoId" value="">
        <input type="hidden" name="convenioId" id="convenioId" value="0">
        <input type="hidden" name="costo_curso" id="costo_curso" value="{{ $formulario->getGrupoCursoCosto() }}">
        <input type="hidden" name="valor_descuento" id="valor_descuento" value="0">
        <input type="hidden" name="total_a_pagar" id="total_a_pagar" value="{{ $formulario->getGrupoCursoCosto() }}">
        <input type="hidden" name="pago_parcial" id="pago_parcial" value="{{ $formulario->TotalPagoRealizado() }}">        
        <input type="hidden" name="valor_pendiente_por_pagar" id="valor_pendiente_por_pagar" value="{{ $formulario->totalPendientePorPagar() }}">        
        @include('formularios._form_legalizar_inscripcion', ['btnText' => 'Legalizar inscripción'])        
    </form>
@endsection