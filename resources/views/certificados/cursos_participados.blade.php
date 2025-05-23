@extends('plantillas.publico')

@section('nameSection', 'Paso 3: Seleccione los certificados que desea descargar')

@section('description')
    A continuación se listan los cursos en los que ha participado. Puede descargar el certificado 
    de aquellos cursos que estén aprobados y cumplan con el porcentaje mínimo de asistencia.
@endsection

@section('content')

<div class="d-flex justify-content-end mb-3">
    <form action="{{ route('certificado.asistencia.logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill">
            <i class="fa fa-sign-out-alt me-1"></i> Finalizar sesión
        </button>
    </form>
</div>


<div class="mb-4 text-center">
    <p class="mb-1"><strong>Participante:</strong> {{ $participante->getNombreCompleto() }}</p>
    <p class="mb-1"><strong>Documento:</strong> {{ $participante->getDocumentoCompleto() }}</p>
    <p>Nota importante</p>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-primary text-center">
            <tr>
                <th>Curso</th>
                <th>Periodo</th>
                <th>Asistencias</th>
                <th>Total sesiones</th>
                <th>% Asistencia</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($participante->cursosParticipados() as $curso)
                <tr>
                    <td style="max-width: 240px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ $curso->nombre_curso }}
                    </td>
                    <td class="text-center">{{ $curso->nombre_calendario }}</td>
                    <td class="text-center">{{ $curso->asistencias }}</td>
                    <td class="text-center">{{ $curso->total_sesiones }}</td>
                    <td class="text-center">{{ $curso->porcentaje_asistencia }}%</td>
                    <td class="text-center">
                        @if($curso->aprobado)
                            <a href="{{ route('certificados.publicos.descargar', ['participanteID' => $participante->getId(), 'grupoID' => $curso->grupo_id]) }}"
                               class="btn btn-sm btn-primary rounded-pill"
                               title="Descargar certificado de asistencia">
                                <i class="fa fa-download me-1"></i> Descargar
                            </a>
                        @else
                            <span class="badge bg-secondary text-white" title="Solo disponible para cursos aprobados">
                                No disponible
                            </span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        No se encontraron cursos registrados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
