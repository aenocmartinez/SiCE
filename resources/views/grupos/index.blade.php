@extends("plantillas.principal")

@section("title", "Grupos")
@section("description", "Listado de grupos.")

@section("content")

@php
    $criterio = isset($criterio) ? $criterio : '';    
    $route = "grupos.index";
    $page = 1;
    if (strlen($criterio)>0) {
        $route = "grupos.buscador-paginador";
    }
@endphp

<div class="row mb-3">

    <div class="row">
        
        <!-- <div class="col-lg-8 col-sm-12">
            <form method="post" action="{{ route('grupos.buscador') }}">
                @csrf
                <div class="pt-0">
                    <div class="input-group">                
                        <button class="btn btn-alt-primary" type="submit">
                            <i class="fa fa-search me-1 opacity-50"></i> 
                        </button>
                        <input type="text" class="form-control" 
                        id="criterio" 
                        name="criterio" 
                        value="{{ $criterio }}"
                        placeholder="Buscar en el tablero">  
                    </div>
                </div>
            </form>
        </div>   -->

        <div class="row">
            <div class="col-lg-8 col-sm-12">
                <form method="post" action="{{ route('grupos.buscador') }}">
                    @csrf
                    <div class="d-flex align-items-center">
                        <!-- Combobox para seleccionar el período -->
                        <div class="me-2">
                            <select name="periodo" id="periodo" class="form-select" style="height: calc(2.25rem + 2px);">
                                <option value="">Seleccione un período</option>
                                @foreach($periodos as $periodo)
                                    <option value="{{ $periodo->getId() }}" {{ $periodoActual->getId() == $periodo->getId() ? 'selected' : '' }}>
                                        {{ $periodo->getNombre() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Input del buscador -->
                        <div class="input-group flex-grow-1">
                            <input type="text" class="form-control" 
                                id="criterio" 
                                name="criterio" 
                                value="{{ $criterio }}"
                                placeholder="Buscar en el tablero">
                            <button class="btn btn-alt-primary" type="submit">
                                <i class="fa fa-search me-1 opacity-50"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Botón para crear grupo -->
            <div class="col-lg-4 col-sm-12 text-end">
                <a href="{{ route('grupos.create') }}" class="btn btn-lg btn-info">
                    <i class="fa fa-circle-plus me-1 opacity-50"></i> Crear grupo
                </a>
            </div>
        </div>

</div>        

<div class="row mt-3">
    <div class="block block-rounded">
        <div class="block-content">

            <table class="table table-vcenter">
                @forelse ($paginate->Records() as $grupo)
                <tr>
                    <td class="fs-sm" style="width: 45%;">                        
                        <h4 class="fw-normal mb-0">
                            {{ $grupo->getNombre() }}
                            <small class="fw-light text-danger">{{ $grupo->estaBloqueado() ? '(Bloqueado)' : '' }} </small>
                        </h4>
                        <small class="fw-light">
                            Curso: {{ $grupo->getNombreCurso() }} ({{ $grupo->getModalidad() }}) <br>
                            Periodo: {{ $grupo->getNombreCalendario() }}<br>
                            Horario: {{ $grupo->getDia() }} / {{ $grupo->getJornada() }}<br>
                            Salón: {{ $grupo->getSalon()->getNombre() }} <br>
                            Orientador: {{ $grupo->getOrientador()->getNombre() }} 
                            @if ($grupo->estaHabilitadoParaPreInscripcion())
                                <br>
                                <span class="text-danger mb-5">Habilitado solo para preinscripción*</span>
                            @endif

                            @if ($grupo->esCalendarioVigente())
                                <br>
                                <span class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info">
                                Total de cupos: {{ $grupo->getCupo() }}
                                </span>                            
                                @php
                                    $class = "fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success"; 
                                    if ($grupo->getTotalCuposDisponibles() == 0) {
                                        $class = "fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-danger-light text-danger"; 
                                    }
                                @endphp
                                <span class="{!! $class !!}">
                                Cupos disponibles: {{ $grupo->getTotalCuposDisponibles() }}
                                </span>
                            @endif

                        </small> 
                    </td>
                    <td class="d-sm-table-cell">

                        @if ($grupo->esCalendarioVigente())
                            <a href="{{ route('grupos.edit', $grupo->getId()) }}" class="fs-xs fw-semibold d-inline-block py-1 px-3 btn rounded-pill btn-outline-secondary">
                                <i class="fa fa-fw fa-pencil-alt"></i> Editar
                            </a>   

                            <form method="POST" action="{{ route('grupos.delete', $grupo->getId()) }}" class="d-inline-block" id="form-del-grupo-{{$grupo->getId()}}">
                                @csrf
                                @method('delete')
                                    <button class="fs-xs fw-semibold py-1 px-3 btn rounded-pill btn-outline-danger"
                                            data-bs-toggle="tooltip" 
                                            title="eliminar grupo" 
                                            type="button"
                                            data-id="{{ $grupo->getId() }}"
                                            onclick="confirmDelete(this)">
                                        <i class="fa fa-fw fa-trash-can"></i> Eliminar
                                    </button>
                            </form>                                              
                        @endif
                        <a href="{{ route('grupos.mas-info', $grupo->getId()) }}" class="fs-xs fw-semibold d-inline-block py-1 px-3 btn rounded-pill btn-outline-info">
                            <i class="fa fa-fw fa-circle-info"></i> Más info
                        </a>
                        <!-- <a href="{{ route('grupos.descargar-listado-participantes', $grupo->getId()) }}" class="fs-xs fw-semibold d-inline-block py-1 px-3 btn rounded-pill btn-outline-info">
                            <i class="fa fa-fw fa-download"></i> XLS
                        </a> -->
                        <a href="{{ route('grupos.descargar-planilla-asistencia', $grupo->getId()) }}" class="fs-xs fw-semibold d-inline-block py-1 px-3 btn rounded-pill btn-outline-info">
                            <i class="fa fa-fw fa-download"></i> Planilla asistencia
                        </a>                        
                    </td>                    
                </tr>
                @empty
                <tr>
                    <td class="text-center">No hay grupos para mostrar</td>
                </tr>
                @endforelse 
            </table>     
            @include('paginator', ['route'=>$route, 'criterio' => $criterio, 'page' => $page, 'periodo' => $periodoActual])

        </div>
    </div>
</div>



<script>
function confirmDelete(button) {
    const grupoId = button.getAttribute('data-id'); 
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, estoy seguro',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(`form-del-grupo-${grupoId}`);
            if (form) {                
                form.submit();
            }
        }
    });
}
</script>

@endsection