@extends("plantillas.principal")

@php
    $titulo = "Cursos del periodo";
@endphp

@section("title", $titulo)

@section("description", "Haga la apertura de los cursos, además, asigne los costos, cupos y modalidad.")

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

        <div class="row push">

            <div class="col-5">

                <label class="form-label" for="area">
                    Área <br><small class="fw-light">seleccione el área para listar los cursos que desea asignar.</small>
                </label>
                <select class="form-select" id="area" name="area">
                    <option value="">Selecciona un área</option>
                    @foreach ($areas as $area)
                        <option 
                            value="{{ $area->getId() }}" 
                            {{ $area->getId() == $areaId ? 'selected' : '' }}                           
                            >{{ $area->getNombre() }}</option>
                    @endforeach
                </select>          

            </div> 
            
            <div class="col-6">
                <h5 class="fw-light text-end mt-5">Listado de cursos abiertos periodo {{ $calendario->getNombre() }}</h5>
            </div>

        </div>

        <div class="row push">

            <div class="col-6">

                
                <div id="cursos_por_area"></div>

                <div id="loading_cursos" class="spinner-border spinner-border-sm text-secondary" style="display: none;">
                    <span class="visually-hidden">Loading...</span>
                </div>

            </div>

            <div class="col-6">
                
                <div id="cursos_abiertos_en_el_periodo"></div>

                <div id="loading_cursos_abiertos_en_el_periodo" class="spinner-border spinner-border-sm text-secondary" style="display: none;">
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

            $("#cursos_por_area").html("");
            $("#cursos_abiertos_en_el_periodo").html("");
            if ($("#area").val().length === 0) {                
                return ;
            }

            const areaId = $('#area').val();
            listarCursos(areaId);
            listarCursosDelPeriodo("{{ $calendario->getId() }}", areaId);
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

    const areaId = "{{  $areaId }}";
    const calendarioId = "{{  $calendario->getid() }}";
    if (areaId > 0){
        listarCursos(areaId);
        listarCursosDelPeriodo(calendarioId, areaId)
    }
    
</script>
@endsection