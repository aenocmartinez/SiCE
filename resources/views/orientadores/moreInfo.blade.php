@extends("plantillas.principal")

@php
    $titulo = "+ información del instructores";
@endphp

@section("title", $titulo)

@section("seccion")
    <a class="link-fx" href="{{ route('orientadores.index') }}">
        Instructores
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

<div class="block block-rounded">
    <div class="block-content">
        <div class="row push">
            <div class="col-md-6">
                <h4 class="fw-light">
                    {{ $orientador->getNombre() }} <br>
                    <small>
                        {{ $orientador->getTipoNumeroDocumento() }} 
                        <a href="{{ route('orientadores.edit', $orientador->getId()) }}">(editar)</a>
                    </small>
                </h4>
                <h5 class="fw-light">
                    <small>                                                
                        <i class="fa fa-fw fa-envelope"></i> 
                            {{ $orientador->getEmailPersonal() }} 
                            {{ !empty($orientador->getEmailInstitucional()) ? '/ ' . $orientador->getEmailInstitucional() : '' }}
                        <br>
                        <i class="fa fa-fw fa-address-book"></i> {{ $orientador->getDireccion() }} <br>
                        <i class="fa fa-fw fa-arrows-spin"></i> {{ $orientador->getEps() }} <br>
                        <i class="fa fa-fw fa-calendar-check"></i> {{ $orientador->getFechaNacimientoFormateada() }} <br>
                    </small>                    
                </h5>

            </div>
            <div class="col-md-6">
                <h5 class="fw-light">
                    <small>                                                
                        <i class="fa fa-fw fa-user-graduate"></i> 
                            {{ $orientador->getNivelEducativo() }} 
                        <br>
                        <i class="fa fa-fw fa-money-check-dollar"></i> Rango salarial: {{ $orientador->getRangoSalarial() }} <br><br>
                        <i class="fa fa-fw fa-chalkboard-user"></i> <br>
                        <p>
                            {{ $orientador->getObservacion() }}
                        </p>
                    </small>                    
                </h5>                
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-xl-4 mt-4">
                <div class="block block-rounded h-100 mb-0">
                    <div class="block-content block-content-full d-flex align-items-center justify-content-between bg-info">
                        <div class="me-3">
                            <p class="fs-sm fw-medium text-white-75 mb-0">
                                Filtros
                            </p>
                        </div>
                    </div>
                    <div class="list-group push">

                        <!-- Filtro de Periodo -->

                        <div class="form-group">
                            <label for="f_periodo" class="mt-4 fs-sm">Periodo</label>
                            <select name="f_periodo" id="f_periodo" class="form-control fs-sm" onchange="redirectToMoreInfo(this)">
                                <option value="">Seleccionar Periodo</option>
                                @foreach ($periodos as $periodo)                                
                                    <option value="{{ $periodo->getId() }}" {{ $periodoFiltro->getId() == $periodo->getId() ? 'selected': ''}}>
                                        {{ $periodo->getNombre() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Fin Filtro de Periodo -->

                        <div class="form-group">
                            <label for="f_area" class="mt-4 fs-sm">Área</label>
                            <select name="f_area" id="f_area" class="form-control fs-sm">
                                <option value="">Seleccionar Área</option>
                                @foreach ($areas as $area)                                
                                    <option value="{{ $area->getNombre() }}">{{ $area->getNombre() }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="f_dia" class="mt-4 fs-sm">Día</label>
                            <select name="f_dia" id="f_dia" class="form-control fs-sm">
                                <option value="">Seleccionar Día</option>
                                @foreach ($dias as $dia)                                
                                    <option value="{{ $dia }}">{{ $dia }}</option>
                                @endforeach
                            </select> 
                        </div>

                        <div class="form-group">
                            <label for="f_jornada" class="mt-4 fs-sm">Jornada</label>
                            <select name="f_jornada" id="f_jornada" class="form-control fs-sm">
                                <option value="">Seleccionar Jornada</option>
                                @foreach ($jornadas as $jornada)                                
                                    <option value="{{ $jornada }}">{{ $jornada }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="f_estado" class="mt-4 fs-sm">Estado</label>
                            <select name="f_estado" id="f_estado" class="form-control fs-sm">
                                <option value="">Seleccionar Estado</option>
                                <option value="Abierto">Abierto</option>
                                <option value="Cerrado">Cerrado</option>
                                <option value="Cancelado">Cancelado</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="f_nombre_curso" class="mt-4 fs-sm">Nombre del Curso</label>
                            <select name="f_nombre_curso" id="f_nombre_curso" class="form-control fs-sm">
                                <option value="">Seleccionar Curso</option>
                                @php
                                    $cursos = array_unique(array_map(function($grupo) {
                                        return $grupo->getNombreCurso();
                                    }, $orientador->misGrupos()));
                                @endphp
                                @foreach ($cursos as $curso)                                
                                    <option value="{{ $curso }}">{{ $curso }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="f_color" class="mt-4 fs-sm">Estado por colores</label>
                            <select name="f_color" id="f_color" class="form-control fs-sm">
                                <option value="">Seleccionar Color</option>
                                <option value="rojo">Rojo - Cancelado</option>
                                <option value="verde">Verde - Abierto</option>
                                <option value="gris">Gris - Cerrado</option>
                            </select>
                        </div>

                        <div id="numero-registros" class="mt-3 text-muted fs-sm"></div>                     
                    </div>
                </div>
            </div>

            <div class="col-md-8 col-xl-8 mt-4">
                <div class="block block-rounded h-100 mb-0">
                    <div class="block-content block-content-full d-flex align-items-center justify-content-between bg-primary-dark">
                        <div class="me-3">
                            <p class="fs-sm fw-medium text-white-75 mb-0">
                                {{ count($orientador->misGrupos()) }} Cursos en el periodo {{ $periodoFiltro->getNombre() }}
                            </p>                        
                        </div>
                    </div>
                    <div class="list-group push">
                        @forelse ($orientador->misGrupos() as $grupo)
                        <div class="list-group-item list-group-item-action text-center" 
                             data-color="{{ strtolower(
                                 $grupo->estaCancelado() ? 'rojo' : 
                                 ($grupo->tieneCuposDisponibles() ? 'verde' : 'gris')
                             ) }}">
                            <small>
                                <h3 class="fw-light text-muted mb-0" id="nombre_curso">
                                    {{ $grupo->getNombreCurso() }}
                                </h3>
                                <h4 id="nombre_area" class="fs-sm mt-1">{{ $grupo->getNombreArea() }}</h4>
                                <span id="nombre_grupo">{{ $grupo->getNombre() }}</span>
                                <span id="dia" class="fs-xl">{{ $grupo->getDia() }}</span> en la <span class="fs-xl" id="jornada">{{ strtolower($grupo->getJornada()) }}</span>
                                <h6 id="estado">
                                    @if ($grupo->estaCancelado())
                                        <span class="badge bg-danger">Cancelado</span>
                                    @elseif ($grupo->tieneCuposDisponibles())
                                        <span class="badge bg-success">Abierto</span>
                                    @else
                                        <span class="badge bg-secondary">Cerrado</span>
                                    @endif                                
                                </h6>
                            </small>

                            <div class="fs-xs fw-bold">
                                <div class="js-pie-chart pie-chart fw-bold mb-1 mt-1" 
                                     data-percent="{{ round(($grupo->getTotalInscritos() / $grupo->getCupo()) * 100) }}" 
                                     data-line-width="3" 
                                     data-size="80" 
                                     data-bar-color="#82b54b" 
                                     data-track-color="#e9e9e9">
                                     <span>{{ $grupo->getTotalInscritos() }}/{{ $grupo->getCupo() }}</span>
                                </div>

                                <div class="text-center d-flex justify-content-center align-items-center">
                                    @if (!$grupo->estaCancelado())
                                    <a href="{{ route('grupos.descargar-planilla-asistencia', $grupo->getId()) }}" 
                                        class="fs-xs fw-semibold d-inline-block py-1 px-3 btn rounded-pill btn-outline-info me-2">
                                        <i class="fa fa-fw fa-download"></i> Planilla asistencia
                                    </a>
                                    <a href="{{ route('grupos.descargar-estado-legalizacion-participantes', $grupo->getId()) }}" 
                                        class="fs-xs fw-semibold d-inline-block py-1 px-3 btn rounded-pill btn-outline-info me-2">
                                        <i class="fa fa-fw fa-download"></i> Legalización participantes
                                    </a>
                                    @endif

                                    @if (!$grupo->estaCancelado() && $grupo->tieneCuposDisponibles() && $periodoFiltro->esVigente())                                         
                                        <form method="POST" action="{{ route('orientador.cancelar-grupo', [$orientador->getId(), $grupo->getId()]) }}" id="form-cancelar-{{$grupo->getId()}}">
                                            @csrf @method('patch')
                                            <button class="btn fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-warning-light text-warning"
                                                    data-bs-toggle="tooltip" 
                                                    title="Cancelar Grupo" 
                                                    type="button"
                                                    data-id="{{ $grupo->getId() }}"
                                                    onclick="confirmCancelar(this)">
                                                Cancelar Grupo
                                            </button>
                                        </form>  
                                     @endif
                                </div>

                            </div>
                        </div>
                        @empty
                        <a class="list-group-item list-group-item-action" href="javascript:void(0)">
                            <small>No tiene cursos asignados</small>
                        </a>                        
                        @endforelse                    
                    </div>

                    <div id="mensaje-sin-registros" style="display:none; text-align:center; margin-top:20px;">
                        <p class="text-muted">No se encontraron registros con los filtros aplicados.</p>
                    </div>

                </div>
            </div>            

        </div>
    </div>
</div>     

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    function ordenarPorJornada(grupos) {
        var ordenJornada = {
            'mañana': 1,
            'tarde': 2,
            'noche': 3
        };

        grupos.sort(function(a, b) {
            var jornadaA = $(a).find('#jornada').text().trim().toLowerCase();
            var jornadaB = $(b).find('#jornada').text().trim().toLowerCase();
            return ordenJornada[jornadaA] - ordenJornada[jornadaB];
        });

        return grupos;
    }

    function normalizeString(str) {
        return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "").trim().toLowerCase();
    }

    function filtrarYOrdenarGrupos() {
        var areaSeleccionada = normalizeString($('#f_area').val());
        var diaSeleccionado = normalizeString($('#f_dia').val());
        var jornadaSeleccionada = normalizeString($('#f_jornada').val());
        var estadoSeleccionado = normalizeString($('#f_estado').val());
        var cursoSeleccionado = normalizeString($('#f_nombre_curso').val());
        var colorSeleccionado = normalizeString($('#f_color').val());
        var gruposVisibles = 0;

        var grupos = [];

        $('.list-group-item').each(function() {
            var grupoArea = normalizeString($(this).find('#nombre_area').text());
            var grupoDia = normalizeString($(this).find('#dia').text());
            var grupoJornada = normalizeString($(this).find('#jornada').text());
            var grupoEstado = normalizeString($(this).find('#estado').text());
            var grupoCurso = normalizeString($(this).find('#nombre_curso').text());
            var grupoColor = normalizeString($(this).data('color'));

            if ((areaSeleccionada === '' || grupoArea.includes(areaSeleccionada)) &&
                (diaSeleccionado === '' || grupoDia.includes(diaSeleccionado)) &&
                (jornadaSeleccionada === '' || grupoJornada.includes(jornadaSeleccionada)) &&
                (estadoSeleccionado === '' || grupoEstado.includes(estadoSeleccionado)) &&
                (cursoSeleccionado === '' || grupoCurso === cursoSeleccionado) &&  // Comparación exacta
                (colorSeleccionado === '' || grupoColor === colorSeleccionado)) {
                grupos.push($(this));
                gruposVisibles++;
            } else {
                $(this).hide();
            }
        });

        grupos = ordenarPorJornada(grupos);

        $.each(grupos, function(index, grupo) {
            grupo.show();
        });

        if (gruposVisibles === 0) {
            $('#mensaje-sin-registros').show();
        } else {
            $('#mensaje-sin-registros').hide();
        }

        $('#numero-registros').text(gruposVisibles + ' registro(s) encontrado(s)');
    }

    $('#f_area, #f_dia, #f_jornada, #f_estado, #f_nombre_curso, #f_color').change(function() {
        filtrarYOrdenarGrupos();
    });

    filtrarYOrdenarGrupos();
});


function confirmCancelar(button) {
    const id = button.getAttribute('data-id'); 
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, estoy seguro',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(`form-cancelar-${id}`);
            if (form) {                
                form.submit();
            }
        }
    });
}

function redirectToMoreInfo(selectElement) {
    const periodoId = selectElement.value;

    if (periodoId) {
        const orientadorId = "{{ $orientador->getId() }}"; 
        const url = `{{ route('orientadores.moreInfo', ['id' => '__ID__']) }}?periodo=__PERIODO__`
            .replace('__ID__', orientadorId)
            .replace('__PERIODO__', periodoId);

        window.location.href = url;
    }
}


</script>

@endsection
