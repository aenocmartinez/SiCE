@php
    $total_a_pagar = 0;
    $numero_cursos = 0;

    if (session()->has('cursos_a_matricular')) {
        $numero_cursos = count(Session::get('cursos_a_matricular'));
        foreach (Session::get('cursos_a_matricular') as $curso) {
            $costo = floatval(preg_replace('/[^0-9]/', '', $curso['totalPago']));
            $total_a_pagar += $costo;
        }
    }        

    $totalCursos = 0;
    $uniqueCourses = [];
    $uniqueInstructors = [];
    $areas = [];

    foreach ($items as $item) {
        $areas[$item->areaId] = $item->areaNombre;
        foreach ($item->grupos as $grupo) {
            $totalCursos++;
            $uniqueCourses[$grupo->cursoNombre] = $grupo->cursoNombre;
            $uniqueInstructors[$grupo->nombreOrientador] = $grupo->nombreOrientador;
        }
    }

    // Ordenar alfabéticamente
    asort($uniqueCourses);
    asort($uniqueInstructors);
    asort($areas);
@endphp

<div class="container-fluid">
    <div class="row">
        <!-- Barra de Filtros -->
        <div class="col-md-9">
            <div class="row bg-light p-3 rounded shadow-sm mb-4">
                <div class="col-md-4 mb-3">
                    <label for="selectCourse" class="form-label fs-xs text-secondary">Nombre del curso</label>
                    <select class="form-select form-select-sm fs-xs rounded-pill" id="selectCourse">
                        <option value="all">Todos los cursos</option>
                        @foreach ($uniqueCourses as $courseName)
                            <option value="{{ $courseName }}">{{ $courseName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="selectInstructor" class="form-label fs-xs text-secondary">Orientador</label>
                    <select class="form-select form-select-sm fs-xs rounded-pill" id="selectInstructor">
                        <option value="all">Todos los orientadores</option>
                        @foreach ($uniqueInstructors as $instructorName)
                            <option value="{{ $instructorName }}">{{ $instructorName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="selectArea" class="form-label fs-xs text-secondary">Área</label>
                    <select class="form-select form-select-sm fs-xs rounded-pill" id="selectArea">
                        <option value="all">Todas las áreas</option>
                        @foreach ($areas as $areaId => $areaNombre)
                            <option value="{{ $areaId }}">{{ $areaNombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="selectDay" class="form-label fs-xs text-secondary">Día</label>
                    <select class="form-select form-select-sm fs-xs rounded-pill" id="selectDay">
                        <option value="all">Todos los días</option>
                        <option value="Lunes">Lunes</option>
                        <option value="Martes">Martes</option>
                        <option value="Miércoles">Miércoles</option>
                        <option value="Jueves">Jueves</option>
                        <option value="Viernes">Viernes</option>
                        <option value="Sábado">Sábado</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="selectTime" class="form-label fs-xs text-secondary">Jornada</label>
                    <select class="form-select form-select-sm fs-xs rounded-pill" id="selectTime">
                        <option value="all">Todas las jornadas</option>
                        <option value="Mañana">Mañana</option>
                        <option value="Tarde">Tarde</option>
                        <option value="Noche">Noche</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="selectType" class="form-label fs-xs text-secondary">Modalidad</label>
                    <select class="form-select form-select-sm fs-xs rounded-pill" id="selectType">
                        <option value="all">Todas las modalidades</option>
                        <option value="Presencial">Presencial</option>
                        <option value="Virtual">Virtual</option>
                    </select>
                </div>
            </div>

            <!-- Listado de Cursos -->
            <div class="row" id="courseList">
                @foreach ($items as $item)
                    @foreach ($item->grupos as $grupo)
                        <div class="col-md-6 col-lg-4 mb-4 course-item" data-area-id="{{ $item->areaId }}" data-modalidad="{{ strtolower($grupo->modalidad) }}" data-instructor="{{ strtolower($grupo->nombreOrientador) }}">
                            <div class="card h-100 shadow-sm border-0 rounded-lg overflow-hidden">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div>
                                        <h5 class="card-title text-primary fs-sm mb-2" id="b_nombre_curso">{{ $grupo->cursoNombre }}</h5>
                                        @if (strlen($grupo->observaciones) > 0)                                            
                                        <span class="d-block fs-xs">
                                            <!-- <strong style="color:red;">¡Importante!</strong> <br> -->
                                            <span style="color:red;">{{ $grupo->observaciones }}</span>
                                        </span>
                                        @endif
                                        <p class="card-text fs-xs text-secondary mb-3">
                                            <strong class="d-block text-dark">{{ $item->areaNombre }}</strong>
                                            <span class="d-block text-muted" id="b_orientador">Orientador: {{ $grupo->nombreOrientador }}</span>
                                            <span class="d-block">
                                              <span id="b_dia">{{ $grupo->dia }}</span> / <span id="b_jornada">{{ $grupo->jornada }}</span>
                                            </span>
                                            <span class="d-block" id="b_costo">
                                                Costo: 
                                                {{ Src\infraestructure\util\FormatoMoneda::PesosColombianos(floatval(preg_replace('/[^0-9]/', '', $grupo->costo))) }}
                                            </span>
                                            <span class="d-block">Modalidad: {{ ucfirst($grupo->modalidad) }}</span>
                                            <span class="d-block">Cupos disponibles: {{ $grupo->cuposDisponibles }}</span>
                                        </p>
                                    </div>
                                    @if ($grupo->cuposDisponibles > 0)
                                    <a href="{{ route('public.agregar_curso_a_matricula', [$participante->getId(), $grupo->grupoId, $formularioId]) }}" 
                                       class="btn btn-outline-success btn-sm fs-xs w-100 rounded-pill text-center">
                                       <i class="fa fa-plus me-1"></i> Agregar curso
                                    </a>  
                                    @else
                                    <span class="badge bg-danger fs-xs w-100 text-center">Cupos agotados</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>

            <!-- Mensaje cuando no se encuentran cursos -->
            <div class="row" id="noCoursesMessage" style="display: none;">
                <div class="col-12">
                    <div class="alert alert-warning text-center fs-xs">
                        No se encontraron cursos que coincidan con los filtros seleccionados. Por favor, ajusta los filtros y vuelve a intentar.
                    </div>
                </div>
            </div>
        </div>

        <!-- Carrito de compra lateral fijo -->
        <div class="col-md-3">
            <div class="position-sticky" style="top: 20px;">
                <div class="bg-white p-3 rounded shadow-sm">
                    <h5 class="fs-xs">
                        <i class="fa fa-shopping-cart me-2"></i> Carrito de Compra
                        <span class="badge bg-primary">{{ $numero_cursos }}</span>
                    </h5>
                    <hr>
                    @if ($numero_cursos > 0)
                    <ul class="list-group mb-3 fs-xs">
                        @foreach (Session::get('cursos_a_matricular') as $curso)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $curso['nombre_curso'] }}</strong><br>
                                <small>{{ $curso['dia'] . " / " . $curso['jornada']}} - 
                                    {{ Src\infraestructure\util\FormatoMoneda::PesosColombianos(floatval(preg_replace('/[^0-9]/', '', $curso['totalPagoFormateado']))) }}
                                </small>
                            </div>
                            <a href="{{ route('public.quitar-curso', [$participante->getId(), $curso['grupoId'], $formularioId]) }}" class="text-danger">
                                <i class="fa fa-trash"></i>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    <div class="d-flex justify-content-between fs-xs">
                        <span>Total:</span>
                        <strong>{{ Src\infraestructure\util\FormatoMoneda::PesosColombianos($total_a_pagar) }}</strong>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('public.pagar-matricula', [$participante->getId()]) }}" class="btn btn-success btn-sm fs-xs w-100 rounded-pill">Ir a Pagar</a>
                    </div>
                    @else
                    <p class="text-muted fs-xs">No has seleccionado ningún curso.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Filtros
    $("#selectCourse, #selectInstructor, #selectArea, #selectDay, #selectTime, #selectType").on("input change", function() {
        filterCourses();
    });

    function filterCourses() {
        let selectedCourse = $("#selectCourse").val().toLowerCase();
        let selectedInstructor = $("#selectInstructor").val().toLowerCase();
        let selectedArea = $("#selectArea").val();
        let selectedDay = $("#selectDay").val();
        let selectedTime = $("#selectTime").val();
        let selectedType = $("#selectType").val();

        let foundCourses = false;

        $(".course-item").each(function() {
            let courseName = $(this).find("#b_nombre_curso").text().toLowerCase();
            let courseArea = $(this).data("area-id");
            let instructorName = $(this).data("instructor").toLowerCase();
            let courseDay = $(this).find("#b_dia").text();
            let courseTime = $(this).find("#b_jornada").text();
            let courseType = $(this).data("modalidad");

            let matchCourse = selectedCourse === "all" || courseName === selectedCourse;
            let matchArea = selectedArea === "all" || courseArea == selectedArea;
            let matchInstructor = selectedInstructor === "all" || instructorName === selectedInstructor;
            let matchDay = selectedDay === "all" || courseDay.includes(selectedDay);
            let matchTime = selectedTime === "all" || courseTime.includes(selectedTime);
            let matchType = selectedType === "all" || courseType === selectedType.toLowerCase();

            if (matchCourse && matchArea && matchInstructor && matchDay && matchTime && matchType) {
                $(this).show();
                foundCourses = true;
            } else {
                $(this).hide();
            }
        });

        if (foundCourses) {
            $("#noCoursesMessage").hide();
        } else {
            $("#noCoursesMessage").show();
        }
    }
});
</script>
