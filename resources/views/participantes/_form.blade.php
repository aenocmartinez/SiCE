<div class="block block-rounded">

    <div class="block-content">

        <div class="row push">

            <h5 class="fw-light link-fx mb-4 text-primary-darker">DATOS BÁSICOS</h5>         

            <div class="col-6">

                <label class="form-label" for="primerApellido">Primer apellido <small class="fw-light">(obligatorio)</small></label>
                <input type="text" 
                    class="form-control @error('primerApellido') is-invalid @enderror" 
                    id="primerApellido" 
                    name="primerApellido" 
                    placeholder="" 
                    value="{{ old('primerApellido', $participante->getPrimerApellido()) }}"                
                    >
                    @error('primerApellido')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                    <br>   

                <label class="form-label" for="primerNombre">Primer nombre <small class="fw-light">(obligatorio)</small></label>
                <input type="text" 
                    class="form-control @error('primerNombre') is-invalid @enderror" 
                    id="primerNombre" 
                    name="primerNombre" 
                    placeholder="" 
                    value="{{ old('primerNombre', $participante->getPrimerNombre()) }}"                
                    >
                    @error('primerNombre')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                    <br>              
                    
                    <label class="form-label" for="tipoDocumento">Tipo de documento <small class="fw-light">(obligatorio)</small></label>
                    <select class="form-select @error('tipoDocumento') is-invalid @enderror" id="tipoDocumento" name="tipoDocumento">            
                        <option value=""> - </option>
                        <option value="CC" {{ $participante->getTipoDocumento() == "CC" ? 'selected' : '' }}>Cédula</option>
                        <option value="TI" {{ $participante->getTipoDocumento() == "TI" ? 'selected' : '' }}>Tarjeta de identidad</option>
                        <option value="CE" {{ $participante->getTipoDocumento() == "CE" ? 'selected' : '' }}>Cédula de extranjería</option>
                        <option value="PP" {{ $participante->getTipoDocumento() == "PP" ? 'selected' : '' }}>Pasaporte</option>
                    </select>
                    @error('tipoDocumento')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror          

                    <br>
                    
                    <label class="form-label" for="fecNacimiento">Fecha de nacimiento <small class="fw-light">(obligatorio)</small></label>
                    <input type="text" 
                       class="js-flatpickr form-control @error('fecNacimiento') is-invalid @enderror" 
                       id="fecNacimiento" 
                       name="fecNacimiento" 
                       placeholder=""
                       value="{{ old('fecNacimiento', $participante->getFechaNacimiento()) }}"
                       >
                    @error('fecNacimiento')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror   
                             
                    <br>  

                    <label class="form-label" for="direccion">Dirección <small class="fw-light">(obligatorio)</small></label>
                    <input type="text" 
                    class="form-control @error('direccion') is-invalid @enderror"
                    id="direccion" 
                    name="direccion" 
                    placeholder="" 
                    value="{{ old('direccion', $participante->getDireccion()) }}"                
                    >
                    @error('direccion')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror                     

                    <br>
                    
                    <label class="form-label" for="telefono">Teléfono <small class="fw-light">(obligatorio)</small></label>
                    <input type="text" 
                    class="form-control @error('telefono') is-invalid @enderror"
                    id="telefono" 
                    name="telefono" 
                    placeholder="" 
                    value="{{ old('telefono', $participante->getTelefono()) }}"                
                    >
                    @error('telefono')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror  
                    
                    <br>
                        <label class="form-label" for="eps">EPS</label>
                        <select class="form-select @error('eps') is-invalid @enderror" id="eps" name="eps">
                        <option value="">Selecciona una eps</option>
                            @foreach ($listaEps as $eps)            
                                <option value="{{ $eps }}" {{ $participante->getEps() == $eps ? 'selected' : '' }}>{{ $eps }}</option>
                            @endforeach
                        </select>                       

            </div>

            <div class="col-6">

                <label class="form-label" for="segundoApellido">Segundo apellido</label>
                <input type="text" 
                    class="form-control @error('segundoApellido') is-invalid @enderror" 
                    id="segundoApellido" 
                    name="segundoApellido" 
                    placeholder="" 
                    value="{{ old('segundoApellido', $participante->getSegundoApellido()) }}"                
                    >
                @error('segundoApellido')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror
                <br>  

                <label class="form-label" for="segundoNombre">Segundo nombre</label>
                <input type="text" 
                    class="form-control @error('segundoNombre') is-invalid @enderror" 
                    id="segundoNombre" 
                    name="segundoNombre" 
                    placeholder="" 
                    value="{{ old('segundoNombre', $participante->getSegundoNombre()) }}"                
                    >
                    @error('segundoNombre')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                    <br>                        
                    
                    <label class="form-label" for="documento">No. documento <small class="fw-light">(obligatorio)</small></label>
                    <input type="text" 
                        class="form-control @error('documento') is-invalid @enderror"
                        id="documento" 
                        name="documento" 
                        placeholder="" 
                        value="{{ old('documento', $participante->getDocumento()) }}"                
                        >
                        @error('documento')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror                     
                

                    <br>        

                    <label class="form-label" for="sexo">Género <small class="fw-light">(obligatorio)</small></label>
                    <select class="form-select @error('sexo') is-invalid @enderror" id="sexo" name="sexo">            
                        <option value=""> - </option>
                        @foreach ($sexo as $s)                            
                            <option value="{{ $s['value'] }}" {{ $participante->getSexo() == $s['value'] ? 'selected' : '' }}>{{ $s['nombre'] }}</option>
                        @endforeach
                    </select>
                    @error('sexo')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror 

                    <br>                      
                    
                    <label class="form-label" for="estadoCivil">Estado civil <small class="fw-light">(obligatorio)</small></label>
                    <select class="form-select @error('estadoCivil') is-invalid @enderror" id="estadoCivil" name="estadoCivil">            
                        <option value=""> - </option>
                        @foreach ($estadoCivil as $s)                            
                            <option value="{{ $s['value'] }}" {{ $participante->getEstadoCivil() == $s['value'] ? 'selected' : '' }}>{{ $s['nombre'] }}</option>
                        @endforeach
                    </select>
                    @error('sexo')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror          
                    <br>     
                    
                    <label class="form-label" for="email">Correo electrónico <small class="fw-light">(obligatorio)</small></label>
                    <input type="email" 
                        class="form-control @error('email') is-invalid @enderror"
                        id="email" 
                        name="email" 
                        placeholder="" 
                        value="{{ old('email', $participante->getEmail()) }}"                
                        >
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror               

            </div>            

        </div>

    </div>

</div>    

<div class="block block-rounded">

    <div class="block-content">

        <div class="row push">

        <h5 class="fw-light link-fx mb-4 text-primary-darker">INFORMACIÓN DE MATRÍCULA</h5> 

            <div class="col-6">

                <label class="form-label" for="calendario">Periodo</label>
                <select class="form-select @error('calendario') is-invalid @enderror" id="calendario" name="calendario">
                    <option value="">Selecciona un periodo</option>
                    @foreach ($calendarios as $calendario)
                        @if ($calendario->esVigente())                            
                            <option 
                                value="{{ $calendario->getId() }}"
                                {{ old('calendario') }}
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
                
                <label class="form-label" for="convenio">Convenio</label>
                <select class="form-select @error('convenio') is-invalid @enderror" id="convenio" name="convenio">
                    <option value=""> - </option>
                    @foreach ($convenios as $convenio)
                        @if ($convenio->esVigente())                            
                            <option 
                                value="{{ $convenio->getId() }}"
                                {{ old('convenio') }}
                                >{{ $convenio->getNombre() }} 
                                @if ($convenio->getDescuento() > 0)                                    
                                    ({{ $convenio->getDescuento() }}% de descuento)
                                @endif
                            </option>
                        @endif
                    @endforeach
                </select>
                @error('convenio')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror                   

            </div>       
            
            <div class="col-6">
                <label class="form-label" for="curso">Curso</label>
                <select class="form-select" id="curso" name="curso"></select>
                <br>                

            </div>

        </div>

    </div>

</div>         


<script src="{{asset('assets/js/oneui.app.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('assets/js/plugins/simplemde/simplemde.min.js')}}"></script>
<script>One.helpersOnLoad(['js-ckeditor', 'js-simplemde']);</script>

<script src="{{asset('assets/js/lib/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/flatpickr/flatpickr.min.js')}}"></script>
<script>One.helpersOnLoad(['js-flatpickr']);</script>

<script>

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
            
            listarOrientadores(cursoCalendarioId, "0");
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
</script>