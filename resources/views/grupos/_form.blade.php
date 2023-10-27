<input type="hidden" id="curso_calendario_id_actual" value="{{ $grupo->getCursoCalendarioId()}}">

<div class="block block-rounded">

    <div class="block-content">

        <div class="row push">

            <div class="col-6">

            <label class="form-label" for="calendario">Calendario</label>
                <select class="form-select @error('calendario') is-invalid @enderror" id="calendario" name="calendario">
                    <option value="">Selecciona un calendario</option>
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
                            {{ old('salon', $grupo->getSalon()->getId()) == $salon->getId() ? 'selected' : '' }}
                            >{{ $salon->getNombre() }}</option>
                    @endforeach
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

            </div>

            <div class="col-6">

                <label class="form-label" for="curso">Curso</label>
                <select class="form-select" id="curso" name="curso"></select>
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
    
    const orientadorIdActual = '{{ $grupo->getOrientadorId() }}';
    const cursoCalendarioIdActual =  '{{ $grupo->getCursoCalendarioId() }}';

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

    const cursoCalendarioId = "{{ $grupo->getCursoCalendarioId() }}";
    const calendarioId = "{{  $grupo->getCalendarioId() }}";

    if (cursoCalendarioId > 0){
        listarCursos(calendarioId, cursoCalendarioIdActual);
        listarOrientadores(cursoCalendarioId, orientadorIdActual);
    }    
    
</script>