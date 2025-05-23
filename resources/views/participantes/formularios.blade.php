@extends("plantillas.principal")

@php
    $titulo = "Formularios de inscripciones";
@endphp

@section("title", $titulo)
@section("description", "")

@section("seccion")
    <a class="link-fx" href="{{ route('participantes.index') }}">
        Participantes
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")
<div class="row mb-4">
    <div class="col-12">
        <div class="block block-rounded">
            <div class="block-content p-3">
                <h4 class="fw-semibold mb-2">{{ $participante->getNombreCompleto() }}</h4>
                <p class="text-muted fs-sm">{{ $participante->getDocumentoCompleto() }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="block block-rounded">
            <div class="block-content">
                <table class="table table-vcenter table-striped">
                    <thead class="bg-primary text-white text-center">
                        <tr>
                            <th style="width: 16%;">Formulario</th>
                            <th style="width: 16%;">Periodo</th>
                            <th style="width: 16%;">Curso</th>
                            <th style="width: 16%;">Fecha de Creación</th>
                            <th style="width: 16%;">Fec. Max. Legalización</th>
                            <th style="width: 16%;">Estado</th>
                            <th style="width: 16%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($formularios as $f)
                            <tr class="fs-xs text-center">
                                <td>{{ $f->getNumero() }}</td>
                                <td>{{ $f->getGrupoCalendarioNombre() }}</td>
                                <td>
                                    <a href="#" class="fs-sm">{{ $f->getGrupoNombreCurso() }}</a>
                                    <br>
                                    <small class="text-muted">
                                        G{{ $f->getGrupoId()  }}: 
                                        {{ $f->getGrupoDia()  }} / {{ $f->getGrupoJornada() }}<br>{{ $f->getGrupoModalidad() }}
                                    </small>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($f->getFechaCreacion())->format('Y-m-d') }}</td>
                                <td>{{ $f->getFechaMaxLegalizacion() }}</td>
                                <td>
                                    {{ $f->getEstado() }}
                                    @if ($f->tieneConvenio())
                                        <br>
                                        <small class="text-info">Convenio: {{ $f->getConvenioNombre() }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="btn-group mb-2">
                                            @if ($f->esCalendarioVigente())
                                                @if ($f->PendienteDePago() || $f->RevisarComprobanteDePago())                            
                                                    <a href="{{ route('participantes.edit-legalizar-inscripcion', [$f->getNumero()]) }}" 
                                                       class="btn btn-sm btn-outline-primary rounded-pill shadow-sm"
                                                       data-bs-toggle="tooltip" 
                                                       title="Legalizar inscripción">
                                                       <i class="fa fa-check"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('participantes.anular-inscripcion', [$f->getNumero(), $participante->getId()]) }}" id="form-del-anular-{{$f->getNumero()}}" class="d-inline">
                                                        @csrf @method('patch')
                                                        <button class="btn btn-sm btn-outline-danger rounded-pill shadow-sm"
                                                                data-bs-toggle="tooltip" 
                                                                title="Anular" 
                                                                type="button"
                                                                data-id="{{ $f->getNumero() }}"
                                                                onclick="confirmAnular(this)">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="btn-group">
                                            <a href="{{ route('formulario-inscripcion.descargar-recibo-matricula', [$f->getParticipanteId(), $f->getGrupoCalendarioId()]) }}" 
                                               class="btn btn-sm btn-outline-info rounded-pill shadow-sm me-1"
                                               data-bs-toggle="tooltip" 
                                               title="Descargar recibo matrícula">
                                               <i class="fa fa-file"></i>
                                            </a>
                                            <a href="{{ route('participantes.ver-detalle-inscripcion', $f->getNumero()) }}" 
                                               class="btn btn-sm btn-outline-success rounded-pill shadow-sm"
                                               data-bs-toggle="tooltip" 
                                               title="Detalle de la inscripción">
                                               <i class="fa fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </td>                    
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="7">No hay formularios para mostrar</td>
                            </tr>
                        @endforelse 
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
function confirmAnular(button) {
    const numeroFormulario = button.getAttribute('data-id');

    Swal.fire({
        title: '¿Estás seguro de anular la inscripción?',
        input: 'textarea',
        inputLabel: 'Motivo de anulación',
        inputPlaceholder: 'Escribe aquí el motivo...',
        inputAttributes: {
            'aria-label': 'Motivo de anulación'
        },
        showCancelButton: true,
        confirmButtonText: 'Sí, anular',
        cancelButtonText: 'Cancelar',
        inputValidator: (value) => {
            if (!value.trim()) {
                return 'Debes indicar un motivo';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(`form-del-anular-${numeroFormulario}`);
            if (form) {
                const motivoInput = document.createElement('input');
                motivoInput.type = 'hidden';
                motivoInput.name = 'motivo_anulacion';
                motivoInput.value = result.value.trim();
                form.appendChild(motivoInput);
                form.submit();
            }
        }
    });
}
</script>

@endsection
