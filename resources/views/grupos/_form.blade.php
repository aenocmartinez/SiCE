@php
    $checked = $grupo->estaBloqueado() ? 'checked' : '';
    $checkedCerradoParaInscripcion = $grupo->estaCerradoParaInscripcion() ? 'checked' : '';
    $checkedHabilitadoParaPreInscripcion = $grupo->estaHabilitadoParaPreInscripcion() ? 'checked' : '';
@endphp

<input type="hidden" id="curso_calendario_id_actual" value="{{ $grupo->getCursoCalendarioId()}}">
<input type="hidden" id="capacidad_salon" name="capacidad_salon" value="{{ old('capacidad_salon', $grupo->getCupo()) }}">

<div class="block block-rounded">

    <div class="block-content">

        <div class="row push">

            <div class="col-6">

            <label class="form-label" for="calendario">Periodo</label>
                <select class="form-select @error('calendario') is-invalid @enderror" id="calendario" name="calendario">
                    <option value="">Selecciona periodo</option>
                    @foreach ($calendarios as $calendario)
                        @if ($calendario->esVigente())                            
                            <option 
                                value="{{ $calendario->getId() }}"
                                {{ old('calendario', $grupo->getCalendarioId()) == $calendario->getId() ? 'selected' : '' }}
                                >{{ $calendario->getNombre() }}
                            </option>
                        @endif
                    @endforeach
                </select>
                @error('calendario')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror                  

                <br>            

                <label class="form-label" for="salon">Salón</label>
                <select class="form-select @error('salon') is-invalid @enderror" id="salon" name="salon">
                    <option value="">Selecciona un salón</option>
                    @foreach ($salones as $salon)
                        <option 
                            value="{{ $salon->getId() }}"
                            data-valor-asignar="{{ $salon->getCapacidad() }}"
                            {{ old('salon', $grupo->getSalon()->getId()) == $salon->getId() ? 'selected' : '' }}
                            >{{ $salon->getNombreYTipoSalon() }} (capacidad: {{ $salon->getCapacidad() }} personas)                            
                        </option>
                    @endforeach
                        <option 
                            value="0" 
                            data-valor-asignar="1000"
                            {{ old('salon', $grupo->getSalon()->getId()) == 0 ? 'selected' : '' }}
                            >Virtual
                        </option>
                </select>
                @error('salon')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror                 

                <br>

                <label class="form-label" for="jornada">Jornada</label>
                <select class="form-select @error('jornada') is-invalid @enderror" id="jornada" name="jornada">
                    <option value="">Selecciona una jornada</option>
                    @foreach ($jornadas as $jornada)
                        <option 
                            value="{{ $jornada }}"
                            {{ old('jornada', $grupo->getJornada()) == $jornada ? 'selected' : '' }}
                            >{{ $jornada }}</option>
                    @endforeach
                </select>    
                @error('jornada')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror   
                
                <br>

                <label class="form-label" for="cupo">Cupos</label>
                    <input type="number" min="0" step="1"
                        class="form-control @error('cupo') is-invalid @enderror" 
                        id="cupo" 
                        name="cupo" 
                        placeholder="cupo" 
                        value="{{ old('cupo', $grupo->getCupo()) }}"                
                        >
                        @error('cupo')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror      
                <br>
                <label class="form-label" for="observaciones">Observaciones</label>
                <textarea class="form-control fs-xs" 
                          id="observaciones" 
                          name="observaciones" 
                          style="height: 130px">{{ old('observaciones', $grupo->getObservaciones()) }}</textarea>                        

            </div>

            <div class="col-6">

                <label class="form-label" for="curso">Curso</label>
                <select class="form-select @error('curso') is-invalid @enderror" id="curso" name="curso">
                    <option value="">Selecciona un curso</option>
                </select>
                @error('curso')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror                  
                <br>

                <label class="form-label" for="dia">Día</label>
                <select class="form-select @error('dia') is-invalid @enderror" id="dia" name="dia">
                    <option value="">Selecciona un día</option>
                    @foreach ($dias as $dia)
                        <option 
                            value="{{ $dia }}"
                            {{ old('dia', $grupo->getDia()) == $dia ? 'selected' : '' }}
                            >{{ $dia }}</option>
                    @endforeach
                </select>   
                @error('dia')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror                   

                <br>

                <label class="form-label" for="orientador">Orientador</label>
                <select class="form-select @error('orientador') is-invalid @enderror" id="orientador" name="orientador">
                    <option value="">Selecciona un orientador</option>
                </select>   
                @error('orientador')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror        
                
                <br>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="bloqueado" name="bloqueado" {{ $checked }}>
                    <label class="form-check-label" for="bloqueado">Bloqueado <small class="fs-xs">(Restringe el grupo a inscripciones solo por administradores.)</small></label>
                </div> 

                <br>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="cerradoParaInscripcion" name="cerradoParaInscripcion" {{ $checkedCerradoParaInscripcion }}>
                    <label class="form-check-label" for="cerradoParaInscripcion">Cerrar grupo <small class="fs-xs">(No permite recibir inscripciones a este grupo.)</small></label>
                </div>
                
                <br>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="habilitadoParaPreInscripcion" name="habilitadoParaPreInscripcion" {{ $checkedHabilitadoParaPreInscripcion }}>
                    <label class="form-check-label" for="cerradoParaInscripcion">Habilitar solo para preinscripción</label>
                </div>                

            </div>

            <div class="col-12 mt-4">
                <button class="btn btn-large btn-info">{{ $btnText }}</button>        
                <a href="{{ route('grupos.index') }}" class="btn btn-large btn-light"> Cancelar</a>
            </div>

        </div>
    
    </div>

</div>    


<script src="{{asset('assets/js/oneui.app.min.js')}}"></script>

<script src="{{asset('assets/js/lib/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/flatpickr/flatpickr.min.js')}}"></script>


<script>One.helpersOnLoad(['js-flatpickr']);</script>

<script>    
    
    const orientadorIdActual = "{{ old('orientador', $grupo->getOrientadorId()) }}";
    const cursoCalendarioIdActual =  "{{ old('curso', $grupo->getCursoCalendarioId()) }}";

    $(document).ready(function(){
        
        $("#calendario").change(function() {
            
            if ($("#calendario").val().length === 0) { 
                $("#curso").html("<select class=\"form-select\" id=\"curso\" name=\"curso\"></select>");   
                $("#orientador").html("<select class=\"form-select\" id=\"orientador\" name=\"orientador\"></select>");            
                return ;
            }            
            const calendarioId = $('#calendario').val();
            listarCursos(calendarioId);
        });

        $("#curso").change(function() {
            $("#salon").val("");
            if ($("#curso").val().length === 0) {       
                $("#orientador").html("<select class=\"form-select\" id=\"orientador\" name=\"orientador\"></select>");                   
                return ;
            }            
            const cursoCalendarioId = $('#curso').val();
            
            listarOrientadores(cursoCalendarioId, orientadorIdActual);
        }); 
        
        $('#salon').change(function() {
            console.log("LLama aqui")
            var valorSeleccionado = $(this).val();
            var valorAsignar = $('#salon option:selected').data('valor-asignar');
            $('#capacidad_salon').val(valorAsignar);
        });

    });


    function listarCursos(calendarioId, cursoCalendarioIdActual) {        
        $("#curso").html("<select class=\"form-select\" id=\"curso\" name=\"curso\"></select>");
        
        var url = "{{ route('grupos.cursos_calendario', ['calendarioId' => ':calendarioId', 'cursoCalendarioIdActual' => ':cursoCalendarioIdActual']) }}";
        url = url.replace(':calendarioId', calendarioId);
        url = url.replace(':cursoCalendarioIdActual', cursoCalendarioIdActual);

        $.ajax({
            url: url,
            type: 'GET',
            success: function(resp) {
                $("#curso").html(resp);
            }            
        });        
    }

    function listarOrientadores(cursoCalendarioId, orientadorIdActual) { 
        
        $("#orientador").html("<select class=\"form-select\" id=\"orientador\" name=\"orientador\"></select>");
        
        var url = "{{ route('grupos.orientadores_por_curso_calendario', ['cursoCalendarioId' => ':cursoCalendarioId', 'orientadorIdActual' => ':orientadorIdActual']) }}";
        url = url.replace(':cursoCalendarioId', cursoCalendarioId);
        url = url.replace(':orientadorIdActual', orientadorIdActual);        

        $.ajax({
            url: url,
            type: 'GET',
            success: function(resp) {
                $("#orientador").html(resp);
            }            
        });        
    } 


    const cursoCalendarioId = "{{ old('curso', $grupo->getCursoCalendarioId()) }}";
    const calendarioId = "{{ old('calendario', $grupo->getCalendarioId()) }}";

    if (cursoCalendarioId > 0){
        listarCursos(calendarioId, cursoCalendarioIdActual);
        listarOrientadores(cursoCalendarioId, orientadorIdActual);
    }      

    const salonId = "{{ $grupo->getSalonId() }}";
    if (salonId >= 0) {
        var valorSeleccionado = $('#salon').val();
            var valorAsignar = $('#salon option:selected').data('valor-asignar');
            $('#capacidad_salon').val(valorAsignar);        
    }
    
</script>