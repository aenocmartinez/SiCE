@extends("plantillas.principal")

@php
    $titulo = "Notificaciones";
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
    <a href="{{ route('notificacion.recordarInicioClase') }}" class="fs-xs fw-semibold d-inline-block py-1 px-3 btn rounded-pill btn-outline-primary">
        <i class="fa fa-fw fa-share-from-square"></i> Recordatorio inicio de clases
    </a>
@endsection