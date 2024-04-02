@php
    $checked = '';
    if ($participante->existe()) {
        $checked = $participante->vinculadoUnicolMayor() ? 'checked' : '';
    }
@endphp

<input type="hidden" name="id" value="{{ $participante->getId() }}">

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
                        <option value="CC" {{ old('tipoDocumento', $participante->getTipoDocumento()) == "CC" ? 'selected' : '' }}>Cédula</option>
                        <option value="TI" {{ old('tipoDocumento', $participante->getTipoDocumento()) == "TI" ? 'selected' : '' }}>Tarjeta de identidad</option>
                        <option value="CE" {{ old('tipoDocumento', $participante->getTipoDocumento()) == "CE" ? 'selected' : '' }}>Cédula de extranjería</option>
                        <option value="PP" {{ old('tipoDocumento', $participante->getTipoDocumento()) == "PP" ? 'selected' : '' }}>Pasaporte</option>
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
                                <option value="{{ $eps }}" {{ old('eps', $participante->getEps()) == $eps ? 'selected' : '' }}>{{ $eps }}</option>
                            @endforeach
                        </select>
                        @error('eps')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror                                             

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
                            <option value="{{ $s['value'] }}" {{ old('sexo', $participante->getSexo()) == $s['value'] ? 'selected' : '' }}>{{ $s['nombre'] }}</option>
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
                            <option value="{{ $s['value'] }}" {{ old('estadoCivil', $participante->getEstadoCivil()) == $s['value'] ? 'selected' : '' }}>{{ $s['nombre'] }}</option>
                        @endforeach
                    </select>
                    @error('estadoCivil')
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
                        <br>       
                        
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="vinculadoUnicolMayor" name="vinculadoUnicolMayor" {{ $checked }}>
                        <label class="form-check-label" for="disponible">¿Tiene vínculo laboral con la Universidad?</label>
                    </div>                          

            </div>            

        </div>

    </div>

</div>    

<div class="block block-rounded">

    <div class="block-content">

        <div class="row push">

            <h5 class="fw-light link-fx mb-4 text-primary-darker">CONTACTO DE EMERGENCIA</h5> 

            <div class="col-6">

                <label class="form-label" for="contactoEmergencia">Nombre del contacto de emergencia <small class="fw-light">(obligatorio)</small></label>
                <input type="text" 
                    class="form-control @error('contactoEmergencia') is-invalid @enderror" 
                    id="contactoEmergencia" 
                    name="contactoEmergencia" 
                    placeholder="" 
                    value="{{ old('contactoEmergencia', $participante->getContactoEmergencia()) }}"                
                    >
                    @error('contactoEmergencia')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror

            </div>

            <div class="col-6">
                <label class="form-label" for="telefonoEmergencia">Número de teléfono del contacto de emergencia <small class="fw-light">(obligatorio)</small></label>
                <input type="text" 
                    class="form-control @error('telefonoEmergencia') is-invalid @enderror" 
                    id="telefonoEmergencia" 
                    name="telefonoEmergencia" 
                    placeholder="" 
                    value="{{ old('telefonoEmergencia', $participante->getTelefonoEmergencia()) }}"                
                    >
                    @error('telefonoEmergencia')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror

            </div>

            <div class="col-12 mt-4">
                <button class="btn btn-large btn-info">{{ $btnText }}</button>        
                <a href="{{ route('participantes.index') }}" class="btn btn-large btn-light"> Cancelar</a>
            </div>

        </div>

    </div>    

</div>

     
<script src="{{asset('assets/js/oneui.app.min.js')}}"></script>

<script src="{{asset('assets/js/lib/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/flatpickr/flatpickr.min.js')}}"></script>
<script>One.helpersOnLoad(['js-flatpickr']);</script>
