@extends("plantillas.principal")

@section("title", "Listado de inscripciones")
@section("description", "Listado de participantes inscritos según periodo.")

@section("content")

@php
    $periodo = $periodo ?? '';
    $estado = $estado ?? '';
    $documento = $documento ?? '';
    $criterio = ["periodo" => $periodo, "estado" => $estado, "documento" => $documento];   
    $route = (strlen($estado) > 0 || strlen($periodo) > 0 || strlen($documento) > 0) ? "formularios.buscador-paginador" : "formularios.index";
    $page = $page ?? 1;
@endphp

<div class="container-fluid py-4">
    <div class="block block-rounded block-content shadow-sm p-4 bg-white">
        <!-- Formulario -->
        <form class="row g-3 align-items-center" action="{{ route('formularios.buscar-inscritos') }}" method="POST">
            @csrf
            <div class="col-md-3">
                <label for="periodo" class="form-label">Periodo</label>
                <select class="form-select form-select-sm shadow-sm" id="periodo" name="periodo">
                    <option value="">Selecciona periodo</option>
                    @foreach ($periodos as $p)
                        <option value="{{ $p->getId() }}" {{ $p->getId() == $periodo ? 'selected' : '' }}>
                            {{ $p->getNombre() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select form-select-sm shadow-sm" id="estado" name="estado">
                    <option value="">Mostrar todo</option>
                    @foreach ($estadoFormulario as $e)   
                        <option value="{{ $e['value'] }}" {{ $e['nombre'] == $estado ? 'selected' : '' }}>
                            {{ $e['nombre'] }}
                        </option>
                    @endforeach
                </select>
            </div>  

            <div class="col-md-4">
                <label for="documento" class="form-label">Buscar por Documento o Formulario</label>
                <input type="text" name="documento" id="documento" value="{{ $documento }}" class="form-control form-control-sm shadow-sm" placeholder="Ingrese documento o número de formulario">
            </div>
            
            <div class="col-md-2 text-end">
                <button class="btn btn-primary btn-sm px-4 rounded-pill shadow-sm mt-4">
                    <i class="fa fa-search me-1"></i> Buscar
                </button>
            </div>
        </form>

        <!-- Tabla -->
        <div class="table-responsive mt-4">
            <table class="table table-striped align-middle">
                <thead class="text-center bg-dark text-white">
                    <tr class="fs-xs">
                        <th class="fw-light">Formulario</th>
                        <th class="fw-light">Participante</th>
                        <th class="fw-light">Convenio</th>
                        <th class="fw-light">Curso</th>
                        <th class="fw-light">Estado</th>
                        <th class="fw-light">Fechas</th> <!-- Columna combinada después de estado -->
                        <th class="fw-light">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($paginate->Records() as $f)
                    <tr class="fs-xs">
                        <td>{{ $f->getNumero() }}</td>
                        <td>{{ $f->getParticipanteNombreCompleto() }}<br><small>{{ $f->getParticipanteTipoYDocumento() }}</small></td>
                        <td>{{ $f->tieneConvenio() ? $f->getConvenioNombre() : 'N/A' }}</td>
                        <td>
                            {{ $f->getGrupoNombreCurso() }}<br>
                            <small>G{{ $f->getGrupoId() }} - {{ $f->getGrupoDia() }} / {{ $f->getGrupoJornada() }}</small><br>
                            <small>Salón {{ $f->getGrupoSalon() }}</small>
                        </td>
                        <td>{{ $f->getEstado() }}</td>
                        <td>
                            <div class="d-flex flex-column">
                                <span><strong>Creación:</strong> {{ \Carbon\Carbon::parse($f->getFechaCreacion())->format('Y-m-d') }}</span>
                                <span><strong>Fecha máx. para legalizar:</strong> {{ \Carbon\Carbon::parse($f->getFechaMaxLegalizacion())->format('Y-m-d') }}</span>
                            </div>
                        </td> <!-- Columna combinada para fechas -->
                        <td class="text-center">
                            <div class="d-flex flex-column align-items-center">
                                <div class="d-flex mb-2">
                                    @if ($calendario->esVigente())
                                        @if ($f->PendienteDePago() || $f->RevisarComprobanteDePago())
                                            <a href="{{ route('formularios.edit-legalizar-inscripcion', [$f->getNumero()]) }}" 
                                               class="btn btn-sm btn-outline-primary rounded-pill shadow-sm me-1"
                                               data-bs-toggle="tooltip" 
                                               title="Legalizar inscripción">
                                               <i class="fa fa-check"></i> 
                                            </a>
                                            <form method="POST" action="{{ route('formularios.anular-inscripcion', [$f->getNumero(), $f->getParticipanteId()]) }}" id="form-del-anular-{{$f->getNumero()}}" class="d-inline">
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
                                <div class="d-flex">
                                    @if (!$f->Anulado() && !$f->Aplazado())
                                        <a href="{{ route('formulario-inscripcion.descargar-recibo-matricula', $f->getParticipanteId()) }}" 
                                           class="btn btn-sm btn-outline-info rounded-pill shadow-sm me-1"
                                           data-bs-toggle="tooltip" 
                                           title="Descargar recibo matrícula">
                                           <i class="fa fa-file"></i> 
                                        </a>
                                    @endif
                                    <a href="{{ route('formularios.ver-detalle-inscripcion', $f->getNumero()) }}" 
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
                        <td class="text-center" colspan="7">No hay formularios para mostrar</td>
                    </tr>
                @endforelse 
                </tbody>
            </table>
        </div>
        <!-- Fin tabla -->

        <x-paginator :paginate="$paginate" :route="$route" :criterio="$criterio" :page="$page" />

    </div>
</div>

<script>
function confirmAnular(button) {
    const numeroFormulario = button.getAttribute('data-id'); 
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, estoy seguro',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(`form-del-anular-${numeroFormulario}`);
            if (form) {                
                form.submit();
            }
        }
    });
}
</script>

@endsection
