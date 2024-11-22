@extends("plantillas.principal")

@php
    $titulo = "Gestión de Cursos por Área";
@endphp

@section("title", $titulo)

@section("description", "Seleccione cursos disponibles y asígnelos al periodo de forma rápida y elegante.")

@section("seccion")
    <a class="link-fx" href="{{ route('calendario.index') }}">
        Periodo académico
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

@php 
    $areaId = 0;
    if (isset($_GET['area_id']) && is_numeric($_GET['area_id'])) {
        $areaId = $_GET['area_id'];
    }
@endphp

<div class="block block-rounded">

    <div class="block-content">

        <!-- Selección de Área -->
        <div class="block-content mb-1">
            <div class="row align-items-center mb-2">
                <div class="col-auto">
                    <label class="form-label block-title fw-light mb-0" for="area">
                        Seleccione Área:
                    </label>
                </div>
                <div class="col-md-4">
                    <select class="form-select fs-sm fw-light" id="area" name="area">
                        <option value="">Selecciona un área</option>
                        @foreach ($areas as $area)
                            <option 
                                value="{{ $area->getId() }}" 
                                {{ $area->getId() == $areaId ? 'selected' : '' }}                           
                                >{{ $area->getNombre() }}</option>
                        @endforeach
                    </select>          
                </div>
            </div>
        </div>

        <!-- Sección Superior: Cursos Asignados en el Periodo -->
        <div class="block-content">
            <h3 class="mb-3 block-title fw-light">Cursos Abiertos en el Periodo</h3>
            <div class="panel-body p-3" style="height: 320px; overflow-y: auto; border: 1px solid #eaeaea; border-radius: 8px;">
                <div id="cursos_abiertos_en_el_periodo"></div>
                <div id="loading_cursos_abiertos_en_el_periodo" class="spinner-border spinner-border-sm text-secondary" style="display: none;">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>        

        <!-- Sección Inferior: Cursos Disponibles -->
        <div class="block-content mb-2">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h1 class="block-title fw-light">Listado de Cursos Para Abrir</h1>
            </div>
            <div class="panel-body p-3" style="height: 320px; overflow-y: auto; border: 1px solid #eaeaea; border-radius: 8px;">
                <div id="cursos_por_area"></div>
                <div id="loading_cursos" class="spinner-border spinner-border-sm text-secondary" style="display: none;">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="{{asset('assets/js/lib/jquery.min.js')}}"></script>
<script>
    $(document).ready(function(){
        $("#area").change(function() {
            const areaId = $('#area').val();
            $("#cursos_por_area").html("");
            $("#cursos_abiertos_en_el_periodo").html("");
            
            if (areaId.length === 0) {                
                return;
            }

            listarCursos(areaId);
            listarCursosDelPeriodo("{{ $calendario->getId() }}", areaId);
        });

        // Asegura que siempre muestre cursos si el área está seleccionada
        const areaId = $("#area").val();
        if (areaId) {
            listarCursos(areaId);
            listarCursosDelPeriodo("{{ $calendario->getId() }}", areaId);
        }

        // Filtrar en tiempo real
        $("#buscar-curso").on("keyup", function() {
            const value = $(this).val().toLowerCase();
            $("#cursos_por_area .curso-item").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });

    function listarCursos(areaId) {
        $("#cursos_por_area").html("");
        $('#loading_cursos').show();
        var calendarioId = '{{ $calendario->getId() }}';
        var url = "{{ route('calendario.cursos_por_area', ['calendarioId' => ':calendarioId', 'areaId' => ':areaId']) }}";
        url = url.replace(':areaId', areaId);
        url = url.replace(':calendarioId', calendarioId);            

        $.ajax({
            url: url,
            type: 'GET',
            success: function(resp) {
                $('#loading_cursos').hide();
                $("#cursos_por_area").html(resp);
            }            
        });
    }

    function listarCursosDelPeriodo(calendarioId, areaId) {
        $("#cursos_abiertos_en_el_periodo").html("");
        $('#loading_cursos_abiertos_en_el_periodo').show();
        
        var url = "{{ route('calendario.cursos_por_calendario', ['calendarioId' => ':calendarioId', 'areaId' => ':areaId']) }}";
        url = url.replace(':areaId', areaId);
        url = url.replace(':calendarioId', calendarioId);            

        $.ajax({
            url: url,
            type: 'GET',
            success: function(resp) {
                $('#loading_cursos_abiertos_en_el_periodo').hide();
                $("#cursos_abiertos_en_el_periodo").html(resp);
            }            
        });        
    }
</script>
@endsection
