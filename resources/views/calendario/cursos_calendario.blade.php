@extends("plantillas.principal")

@php
    $titulo = "Gestión de Apertura de Cursos";
@endphp

@section("title", $titulo)

@section("description", "Seleccione el área y gestione los cursos disponibles para apertura.")

@section("subseccion")
    <a class="link-fx" href="{{ route('calendario.index') }}">
        Periodo académico
    </a>
@endsection

@section("content")

@php 
    $areaId = 0;
    if (isset($_GET['area_id']) && is_numeric($_GET['area_id'])) {
        $areaId = $_GET['area_id'];
    }
@endphp

<div class="block block-rounded">

    <!-- Selección de Área -->
    <div class="block-content">
        <div class="row align-items-center">
            <div class="col-md-12 text-center">
                <label class="form-label block-title fw-light mb-0" for="area">
                    Seleccione Área:
                </label>
            </div>
            <div class="col-md-12">
                <select class="form-select fs-sm fw-light mb-3" id="area" name="area">
                    <option value="">Selecciona un área</option>
                    @foreach ($areas as $area)
                        <option 
                            value="{{ $area->getId() }}" 
                            {{ $area->getId() == $areaId ? 'selected' : '' }}>
                            {{ $area->getNombre() }}
                        </option>
                    @endforeach
                </select>          
            </div>
        </div>
    </div>

</div>

<div class="row">
    <!-- Cursos disponibles -->
    <div class="col-5">
        <div class="block block-rounded">
            <div class="block-content">
                <div id="cursos_por_area">
                    <p class="text-center text-muted fs-xs">No hay registros para mostrar.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Cursos abiertos -->
    <div class="col-7">
        <div class="block block-rounded">
            <div class="block-content">
                <form id="guardar-cursos-form" action="{{ route('calendario.agregar_varios_cursos') }}" method="POST">                    
                    @csrf
                    <input type="hidden" name="area_id" id="area_id" value="{{ $areaId }}">

                    <!-- Inicia la tabla de cursos -->
                    <div class="table-container border rounded p-2 mb-3">
                        <table class="table table-striped fs-xs">
                            <thead>
                                <tr class="text-center">
                                    <th width="25%" class="fs-xs">Curso</th>
                                    <th width="40%" class="fs-xs">Costo</th>
                                    <th width="25%" class="fs-xs">Modalidad</th>
                                    <th width="5%" class="fs-xs"></th>
                                </tr>
                            </thead>
                            <tbody id="cursos_abiertos_en_el_periodo">
                                <tr class="no-data">
                                    <td colspan="4" class="text-center fs-xs">No hay registros para mostrar.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button id="idGuardarCambios" class="btn btn-alt-primary fw-light w-100 mt-3 mb-4" style="display: none;">Guardar cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/lib/jquery.min.js') }}"></script>
<script>

$(document).ready(function () {

    // Cargar cursos abiertos desde el servidor
    function listarCursosDelPeriodo(calendarioId, areaId) {
        $("#cursos_abiertos_en_el_periodo").html(""); // Limpia la tabla
        const url = "{{ route('calendario.cursos_por_calendario', ['calendarioId' => ':calendarioId', 'areaId' => ':areaId']) }}"
            .replace(":areaId", areaId)
            .replace(":calendarioId", calendarioId);

        $.ajax({
            url: url,
            type: 'GET',
            success: function (resp) {
                if (resp.trim().length > 0) {
                    $("#cursos_abiertos_en_el_periodo").html(resp); // Inserta contenido desde el Blade
                } else {
                    // const noDataRow = `
                    //     <tr class="no-data">
                    //         <td colspan="4" class="text-center fs-xs">No hay registros para mostrar.</td>
                    //     </tr>
                    // `;
                    // $("#cursos_abiertos_en_el_periodo").html(noDataRow);
                }
                toggleEmptyMessageAndSaveButton();
            },
            error: function () {
                // const noDataRow = `
                //     <tr class="no-data">
                //         <td colspan="4" class="text-center fs-xs">No hay registros para mostrar.</td>
                //     </tr>
                // `;
                // $("#cursos_abiertos_en_el_periodo").html(noDataRow);
            }
        });
    }

    // Mostrar u ocultar el mensaje vacío y el botón "Guardar cambios"
    function toggleEmptyMessageAndSaveButton() {
        const hasCourses = $("#cursos_abiertos_en_el_periodo tr input[name^='cursos']").length > 0;

        if (hasCourses) {
            $("#cursos_abiertos_en_el_periodo tr.no-data").remove();
            $("#idGuardarCambios").show(); 
        } else {            
            const noDataRow = `
                <tr class="no-data">
                    <td colspan="4" class="text-center fs-xs">No hay registros para mostrar.</td>
                </tr>
            `;
            $("#cursos_abiertos_en_el_periodo").append(noDataRow);
            // $("#idGuardarCambios").hide(); 
        }
    }

    $(document).on("click", ".add-course", function () {
        const courseId = $(this).data("id");
        const courseName = $(this).data("nombre");

        const existingCourses = $(`#cursos_abiertos_en_el_periodo input[name^="cursos"][value="${courseId}"]`).length;
        if (existingCourses >= 2) {
            alert("Este curso ya está agregado dos veces.");
            return;
        }

        let index = 0;
        $('#cursos_abiertos_en_el_periodo tr').each(function () {
            const inputName = $(this).find('input[name^="cursos["]').attr('name');
            if (inputName) {
                const match = inputName.match(/\[(\d+)\]/);
                if (match) {
                    const currentIndex = parseInt(match[1]);
                    if (currentIndex >= index) {
                        index = currentIndex + 1;
                    }
                }
            }
        });

        const newRow = `
            <tr>
                <td>
                    <input type="hidden" name="cursos[${index}][curso_id]" value="${courseId}">
                    ${courseName}
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm cost-input fs-xs" name="cursos[${index}][costo]" value="0">
                </td>
                <td>
                    <select class="form-select form-select-sm fs-xs" name="cursos[${index}][modalidad]">
                        <option value="Presencial">Presencial</option>
                        <option value="Virtual">Virtual</option>
                    </select>
                </td>
                <td class="text-center">
                    <button class="btn btn-danger btn-sm fw-light remove-course fs-xs">X</button>
                </td>
            </tr>
        `;
        $("#cursos_abiertos_en_el_periodo").prepend(newRow);
        toggleEmptyMessageAndSaveButton();
    });

    $(document).on("click", ".remove-course", function () {
        $(this).closest("tr").remove();
        toggleEmptyMessageAndSaveButton();
    });

    $(document).on("input", ".cost-input", function () {
        const valor = parseInt($(this).val().replace(/[^0-9]/g, "")) || 0;
        const formato = new Intl.NumberFormat("es-CO", {
            style: "currency",
            currency: "COP",
            maximumFractionDigits: 0,
        }).format(valor);
        $(this).val(formato);
    });

    $("#area").change(function () {
        const areaId = $(this).val();
        $("#cursos_por_area").html("<p class='text-center text-muted fs-xs'>Cargando...</p>");

        if (!areaId) {
            $("#cursos_por_area").html("<p class='text-center text-muted fs-xs'>No hay registros para mostrar.</p>");
            return;
        }

        $("#area_id").val(areaId);
        listarCursos(areaId);
        listarCursosDelPeriodo("{{ $calendario->getId() }}", areaId);
    });

    function listarCursos(areaId) {
        const calendarioId = '{{ $calendario->getId() }}';
        const url = "{{ route('calendario.cursos_por_area', ['calendarioId' => ':calendarioId', 'areaId' => ':areaId']) }}"
            .replace(":areaId", areaId)
            .replace(":calendarioId", calendarioId);

        $.ajax({
            url: url,
            type: "GET",
            success: function (resp) {
                $("#cursos_por_area").html(resp); 
            },
            error: function () {
                // $("#cursos_por_area").html("<p class='text-center text-muted fs-xs'>No hay registros para mostrar.</p>");
            },
        });
    }

    const initialAreaId = $("#area").val();
    if (initialAreaId) {
        listarCursos(initialAreaId);
        listarCursosDelPeriodo("{{ $calendario->getId() }}", initialAreaId);
    }
});
</script>
@endsection
