<div class="block block-rounded">

    <div class="block-content">

        <!-- Formulario de búsqueda (siempre visible) -->
        <div class="row align-items-end push">
            <div class="col-md-5">
                <!-- Listado de calendarios -->
                <label class="form-label" for="calendario">Periodo</label>
                <select class="form-select @error('calendario') is-invalid @enderror" id="calendario" name="calendario">
                    <option value="{{ $calendario->getId() }}"
                        {{ old('calendario', $calendarioId) == $calendario->getId() ? 'selected' : '' }}>
                        {{ $calendario->getNombre() }}
                    </option>
                </select>
                @error('calendario')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="col-md-5">
                <!-- Listado de áreas -->
                <label class="form-label" for="area">Área</label>
                <select class="form-select @error('area') is-invalid @enderror" id="area" name="area">
                    <option value="">Selecciona una área</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area->getId() }}" {{ old('area', $areaId) == $area->getId() ? 'selected' : '' }}>
                            {{ $area->getNombre() }}
                        </option>
                    @endforeach
                </select>
                @error('area')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="col-md-2 d-flex justify-content-end">
                <button id="buscarCursos" class="btn btn-large btn-info">Buscar cursos</button>
            </div>
        </div>

        <!-- Búsqueda avanzada (solo visible si hay registros en la tabla) -->
        <div class="row push d-none" id="busquedaAvanzadaLink">
            <div class="col-12 d-flex justify-content-end">
                <a href="#" id="toggleBusquedaAvanzada" class="fs-xs text-info">Búsqueda avanzada</a>
            </div>
        </div>

        <!-- Filtros de búsqueda avanzada (oculto inicialmente) -->
        <div id="busquedaAvanzada" class="row push d-none">
            <div class="col-12">
                <div class="border rounded p-3 bg-light">
                    <h5 class="fs-sm fw-semibold mb-3">Filtrar resultados</h5>
                    <div class="row">
                        <div class="col-md-2">
                            <label class="form-label fs-xs" for="f_modalidad">Modalidad</label>
                            <select class="form-select fs-xs" id="f_modalidad" name="f_modalidad">
                                <option value="">Todas las modalidades</option>
                                <option value="presencial">Presencial</option>
                                <option value="virtual">Virtual</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fs-xs" for="f_jornada">Jornada</label>
                            <select class="form-select fs-xs" id="f_jornada" name="f_jornada">
                                <option value="">Todas las jornadas</option>
                                <option value="mañana">Mañana</option>
                                <option value="tarde">Tarde</option>
                                <option value="noche">Noche</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fs-xs" for="f_dia">Día de la semana</label>
                            <select class="form-select fs-xs" id="f_dia" name="f_dia">
                                <option value="">Todos los días</option>
                                <option value="lunes">Lunes</option>
                                <option value="martes">Martes</option>
                                <option value="miércoles">Miércoles</option>
                                <option value="jueves">Jueves</option>
                                <option value="viernes">Viernes</option>
                                <option value="sábado">Sábado</option>
                                <option value="domingo">Domingo</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fs-xs" for="f_curso">Curso</label>
                            <select class="form-select fs-xs" id="f_curso" name="f_curso">
                                <option value="">Todos los cursos</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fs-xs" for="f_orientador">Instructor</label>
                            <select class="form-select fs-xs" id="f_orientador" name="f_orientador">
                                <option value="">Todos los instructores</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resultados -->
        <div class="row push">
            <div class="col-12">
                <p id="no-results" class="text-center text-muted fs-xs d-none">No hay grupos para mostrar para los filtros aplicados</p>
                <table class="table table-vcenter mt-4">
                    @forelse ($grupos as $grupo)
                    @if ($grupo->estaCancelado())
                        @php
                            continue;
                        @endphp
                    @endif
                    <tr class="resultado">
                        <td class="fs-xs" style="width: 40%;">
                            <p class="fw-normal mb-0">
                                <span id="b_codigo_grupo">{{ $grupo->getCodigoGrupo() }}</span>
                                <span id="b_nombre_curso">{{ $grupo->getNombreCurso() }}</span>
                                <span id="b_modalidad">({{ $grupo->getModalidad() }})</span>                            
                                <br>
                                <span style="font-size: 12px" id="b_orientador">
                                    Instructor/a: {{ $grupo->getNombreOrientador() }}
                                </span>
                            </p>
                        </td>
                        <td class="fs-xs" style="width: 20%;">
                            <p class="fw-normal mb-0">
                                <span id="b_dia">{{ $grupo->getDia() }}</span> / 
                                <span id="b_jornada">{{ $grupo->getJornada() }}</span>
                            </p>
                        </td>
                        <td class="fs-xs" style="width: 15%;">
                            <p class="fw-normal mb-0">{{ $grupo->getCostoFormateado() }}</p>
                        </td>
                        <td class="fs-xs" style="width: 20%;">
                            @php
                                $class = "fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success"; 
                                if ($grupo->getTotalCuposDisponibles() == 0) {
                                    $class = "fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-danger-light text-danger"; 
                                }
                            @endphp
                            <span class="{!! $class !!}">
                            Cupos disponibles: {{ $grupo->getTotalCuposDisponibles() }}
                            </span>
                        </td>                    
                        <td class="text-end" style="width: 5%;">
                            <!-- Formulario -->
                            @if ($grupo->getTotalCuposDisponibles() > 0)
                            <a href="{{ route('formulario-inscripcion.paso-4', [$participante->getId(), $grupo->getId()]) }}" 
                                    class="btn fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info"
                                    data-bs-toggle="tooltip" 
                                    title="Seleccionar">
                                    Seleccionar
                            </a>                        
                            @endif
                            <!-- Fin formulario -->
                        </td>                    
                    </tr>
                    @empty
                        @if (!$calendario->existe())                    
                            <tr>
                                <td class="text-center">No hay grupos para mostrar</td>
                            </tr>
                        @endif
                    @endforelse 
                </table>
            </div>
        </div>
    
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Mostrar "Búsqueda avanzada" solo si hay registros en la tabla
        if ($('table tbody tr.resultado').length > 0) {
            $('#busquedaAvanzadaLink').removeClass('d-none');
        }

        $('#toggleBusquedaAvanzada').click(function(e) {
            e.preventDefault();
            $('#busquedaAvanzada').toggleClass('d-none');
            $(this).text($('#busquedaAvanzada').hasClass('d-none') ? 'Búsqueda avanzada' : 'Ocultar búsqueda avanzada');
        });

        // Poblar select de cursos y orientadores a partir de la tabla
        let cursos = new Set();
        let orientadores = new Set();
        $('table tbody tr.resultado').each(function() {
            cursos.add($(this).find('#b_nombre_curso').text().trim());
            orientadores.add($(this).find('#b_orientador').text().trim());
        });

        cursos.forEach(function(curso) {
            $('#f_curso').append('<option value="' + curso + '">' + curso + '</option>');
        });

        orientadores.forEach(function(orientador) {
            $('#f_orientador').append('<option value="' + orientador + '">' + orientador + '</option>');
        });

        // Filtrado automático
        $('#f_modalidad, #f_jornada, #f_dia, #f_curso, #f_orientador').on('change', function() {
            let modalidad = $('#f_modalidad').val().toLowerCase();
            let jornada = $('#f_jornada').val().toLowerCase();
            let dia = $('#f_dia').val().toLowerCase();
            let curso = $('#f_curso').val().toLowerCase();
            let orientador = $('#f_orientador').val().toLowerCase();

            let resultados = 0;

            $('table tbody tr.resultado').each(function() {
                let row = $(this);
                let b_modalidad = row.find('#b_modalidad').text().toLowerCase();
                let b_jornada = row.find('#b_jornada').text().toLowerCase();
                let b_dia = row.find('#b_dia').text().toLowerCase();
                let b_curso = row.find('#b_nombre_curso').text().toLowerCase();
                let b_orientador = row.find('#b_orientador').text().toLowerCase();

                if ((modalidad === "" || b_modalidad.includes(modalidad)) &&
                    (jornada === "" || b_jornada.includes(jornada)) &&
                    (dia === "" || b_dia.includes(dia)) &&
                    (curso === "" || b_curso === curso) &&
                    (orientador === "" || b_orientador.includes(orientador))) {
                    row.show();
                    resultados++;
                } else {
                    row.hide();
                }
            });

            // Mostrar mensaje si no se encuentran resultados
            if (resultados === 0) {
                $('#no-results').removeClass('d-none');
            } else {
                $('#no-results').addClass('d-none');
            }
        });
    });
</script>
