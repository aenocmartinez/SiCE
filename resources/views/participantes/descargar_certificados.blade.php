@extends('plantillas.principal')

@section('title', 'Descargar certificados')
@section('description', '')

@section("seccion")
    <a class="link-fx" href="{{ route('participantes.index') }}">
        Participantes
    </a>

@endsection
@section("subseccion", 'Descargar certificados')

@section('content')

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start">
                <div>
                    <!-- <h4 class="mb-1">Cursos aprobados</h4> -->
                    <p class="mb-0 text-muted">
                        Participante: {{ $participante->getNombreCompleto() }}<br>
                        Documento: {{ $participante->getDocumentoCompleto() }}<br>
                        Email: {{ $participante->getEmail() }}<br>
                        Teléfono: {{ $participante->getTelefono() }}
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="block block-rounded">
        <div class="block-content">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr class="text-center">
                        <th>Curso</th>
                        <th>Periodo</th>
                        <th>Asistencias</th>
                        <th>Total Sesiones</th>
                        <th>Asistencia (%)</th>
                        <th class="text-center">Certificado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($participante->cursosParticipados() as $curso)
                    <tr>
                        <td>{{ $curso->nombre_curso }}</td>
                        <td class="text-center">{{ $curso->nombre_calendario}}</td>
                        <td class="text-center">{{ $curso->asistencias }}</td>
                        <td class="text-center">{{ $curso->total_sesiones }}</td>
                        <td class="text-center">{{ $curso->porcentaje_asistencia }}%</td>
                        <td class="text-center">
                            @if($curso->aprobado)
                                <a href="{{ route('certificados.descargar', ['participanteID' => $participante->getId(), 'grupoID' => $curso->grupo_id]) }}"
                                class="btn btn-sm btn-primary rounded-pill"
                                title="Descargar certificado">
                                    <i class="fa fa-download me-1"></i> Descargar
                                </a>
                            @else
                                <span class="badge bg-danger text-white">No aprobado</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No se encontraron cursos aprobados</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
