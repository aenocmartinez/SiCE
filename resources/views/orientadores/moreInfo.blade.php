@extends("plantillas.principal")

@php
    $titulo = "+ información del orientador";
@endphp

@section("title", $titulo)

@section("seccion")
    <a class="link-fx" href="{{ route('orientadores.index') }}">
        Orientadores
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

<div class="block block-rounded">
    <div class="block-content">
        <div class="row push">
            <div class="col-6">
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
            <div class="col-6">
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
                        Aplicar Filtros
                        </p>
                    </div>
                    </div>
                    <div class="list-group push">
                        <label for="f_area" class="mt-4">Área</label>
                        <select name="f_area" id="f_area" class="form-control fs-sm">
                            <option value=""></option>
                            @foreach ($areas as $area)                                
                                <option value="{{ $area->getNombre() }}">{{ $area->getNombre() }}</option>
                            @endforeach
                        </select>

                        <label for="f_dia" class="mt-4">Día</label>
                        <select name="f_dia" id="f_dia" class="form-control fs-sm">
                            <option value=""></option>
                            @foreach ($dias as $dia)                                
                                <option value="{{ $dia }}">{{ $dia }}</option>
                            @endforeach
                        </select> 
                        
                        <label for="f_jornada" class="mt-4">Jornada</label>
                        <select name="f_jornada" id="f_jornada" class="form-control fs-sm">
                            <option value=""></option>
                            @foreach ($jornadas as $jornada)                                
                                <option value="{{ $jornada }}">{{ $jornada }}</option>
                            @endforeach
                        </select>

                        <label for="f_estado" class="mt-4">Estado</label>
                        <select name="f_estado" id="f_estado" class="form-control fs-sm">
                            <option value=""></option>
                            <option value="Abierto">Abierto</option>
                            <option value="Cerrado">Cerrado</option>
                        </select>

                        <label for="f_nombre_curso" class="mt-4">Nombre del Curso</label>
                        <select name="f_nombre_curso" id="f_nombre_curso" class="form-control fs-sm">
                            <option value=""></option>
                            @php
                                $cursos = array_unique(array_map(function($grupo) {
                                    return $grupo->getNombreCurso();
                                }, $orientador->misGrupos()));
                            @endphp
                            @foreach ($cursos as $curso)                                
                                <option value="{{ $curso }}">{{ $curso }}</option>
                            @endforeach
                        </select>

                        <!-- Aquí se agrega el elemento para mostrar el número de registros encontrados -->
                        <div id="numero-registros" class="mt-3 text-muted fs-sm"></div>                     
                    </div>
                </div>
            </div>

            <div class="col-md-8 col-xl-8 mt-4">
                <div class="block block-rounded h-100 mb-0">
                    <div class="block-content block-content-full d-flex align-items-center justify-content-between bg-primary-dark">
                    <div class="me-3">
                        <p class="fs-sm fw-medium text-white-75 mb-0">
                        {{ count($orientador->misGrupos()) }} Cursos en el periodo vigente
                        </p>                        
                    </div>
                    </div>
                    <div class="list-group push">
                        @forelse ($orientador->misGrupos() as $grupo)
                        <div class="list-group-item list-group-item-action text-center">
                            <small>
                                <span class="fw-bold text-muted" id="nombre_curso">{{ $grupo->getNombreCurso() }}</span>
                                <br>
                                <span class="fw-bold text-muted" id="nombre_area">{{ $grupo->getNombreArea() }}</span>
                                <br>                                                                
                                <span class="text-muted fs-xs">
                                <span id="nombre_grupo">{{ $grupo->getNombre() }}</span>
                                <span id="dia">{{ $grupo->getDia() }}</span> en la <span id="jornada">{{ strtolower($grupo->getJornada()) }}</span>
                                <br>
                                </span>
                                <span class="fw-bold text-muted fs-xs" id="estado">
                                {{ $grupo->tieneCuposDisponibles() ? 'Abierto' : 'Cerrado' }}
                                <br>
                                </span>                                
                            </small>

                            <div class="fs-xs fw-bold">

                                <!-- Pie Chart Container -->
                                <div class="js-pie-chart pie-chart fw-bold mb-1 mt-1" 
                                     data-percent="{{ round(($grupo->getTotalInscritos() / $grupo->getCupo()) * 100) }}" 
                                     data-line-width="3" 
                                     data-size="60" 
                                     data-bar-color="#82b54b" 
                                     data-track-color="#e9e9e9">
                                     <span>{{ $grupo->getTotalInscritos() }}/{{ $grupo->getCupo() }}</span>
                                </div>
                                <div class="text-center">
                                    <a href="{{ route('grupos.descargar-planilla-asistencia', $grupo->getId()) }}" 
                                        class="fs-xs fw-semibold d-inline-block py-1 px-3 btn rounded-pill btn-outline-info">
                                        <i class="fa fa-fw fa-download"></i> Planilla asistencia
                                    </a>
                                    <a href="{{ route('grupos.descargar-estado-legalizacion-participantes', $grupo->getId()) }}" 
                                        class="fs-xs fw-semibold d-inline-block py-1 px-3 btn rounded-pill btn-outline-info">
                                        <i class="fa fa-fw fa-download"></i> Legalización participantes
                                    </a>                                    
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
    // Función para definir el orden de las jornadas
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

    // Función para normalizar cadenas eliminando acentos y caracteres especiales
    function normalizeString(str) {
        return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
    }

    // Función para filtrar y ordenar los grupos
    function filtrarYOrdenarGrupos() {
        var areaSeleccionada = normalizeString($('#f_area').val());
        var diaSeleccionado = normalizeString($('#f_dia').val());
        var jornadaSeleccionada = normalizeString($('#f_jornada').val());
        var estadoSeleccionado = normalizeString($('#f_estado').val());
        var cursoSeleccionado = normalizeString($('#f_nombre_curso').val());
        var gruposVisibles = 0;

        // Crear un array con los grupos para filtrar y ordenar
        var grupos = [];

        $('.list-group-item').each(function() {
            var grupoArea = normalizeString($(this).find('#nombre_area').text());
            var grupoDia = normalizeString($(this).find('#dia').text());
            var grupoJornada = normalizeString($(this).find('#jornada').text());
            var grupoEstado = normalizeString($(this).find('#estado').text());
            var grupoCurso = normalizeString($(this).find('#nombre_curso').text());

            if ((areaSeleccionada === '' || grupoArea.includes(areaSeleccionada)) &&
                (diaSeleccionado === '' || grupoDia.includes(diaSeleccionado)) &&
                (jornadaSeleccionada === '' || grupoJornada.includes(jornadaSeleccionada)) &&
                (estadoSeleccionado === '' || grupoEstado.includes(estadoSeleccionado)) &&
                (cursoSeleccionado === '' || grupoCurso === cursoSeleccionado)) {  // Uso de === para comparación exacta
                grupos.push($(this));
                gruposVisibles++;
            } else {
                $(this).hide();
            }
        });

        // Ordenar los grupos por jornada
        grupos = ordenarPorJornada(grupos);

        // Mostrar los grupos ordenados
        $.each(grupos, function(index, grupo) {
            grupo.show();
        });

        // Mostrar u ocultar el mensaje de "No se encontraron registros"
        if (gruposVisibles === 0) {
            $('#mensaje-sin-registros').show();
        } else {
            $('#mensaje-sin-registros').hide();
        }

        // Actualizar el número de registros encontrados
        $('#numero-registros').text(gruposVisibles + ' registro(s) encontrado(s)');
    }

    // Escuchar los cambios en los selectores de filtros
    $('#f_area, #f_dia, #f_jornada, #f_estado, #f_nombre_curso').change(function() {
        filtrarYOrdenarGrupos();
    });

    // Filtrar y ordenar inicialmente en caso de que haya valores predeterminados
    filtrarYOrdenarGrupos();
});

</script>

@endsection
