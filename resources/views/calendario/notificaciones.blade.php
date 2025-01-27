@extends("plantillas.principal")

@php
    $titulo = "Notificaciones";
    $fechaInicioClases = \Src\infraestructure\util\FormatoFecha::fechaFormateadaA5DeAgostoDe2024($periodo->getFechaInicioClase());
@endphp

@section("title", $titulo)

@section("description", "Histórico de comunicados enviados")

@section("seccion")
    <a class="link-fx" href="{{ route('calendario.index') }}">
        Periodo académico
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Recordatorio inicio de clases</h5>
                    <p class="card-text">
                        Envía un recordatorio a los participantes sobre el inicio de clases.
                    </p>
                    <p class="text-muted">
                        Fecha de inicio: <strong>{{ $fechaInicioClases }}</strong>
                    </p>
                    <a href="{{ route('notificacion.enviar') }}?tipo=inicioClase" class="btn btn-outline-primary">
                        <i class="fa fa-fw fa-share-from-square"></i> Enviar
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Recordatorio participantes pendientes de legalizar</h5>
                    <p class="card-text">
                        Envía un recordatorio a los participantes que aún no han legalizado su inscripción.
                    </p>
                    <p class="text-muted">
                        Fecha de inicio: <strong>{{ $fechaInicioClases }}</strong>
                    </p>
                    <a href="{{ route('notificacion.enviar') }}?tipo=noLegalizados" class="btn btn-outline-primary">
                        <i class="fa fa-fw fa-share-from-square"></i> Enviar
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
