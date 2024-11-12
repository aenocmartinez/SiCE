@php
    $checked = '';
    if ($participante->existe()) {
        $checked = $participante->vinculadoUnicolMayor() ? 'checked' : '';
    }
@endphp

<input type="hidden" name="id" id="id" value="{{ $participante->getId() }}">

<div class="row push">

    <div class="col-6">

        <div class="form-floating">
        <input type="text" 
                class="form-control fs-sm @error('primerNombre') is-invalid @enderror" 
                id="primerNombre" 
                name="primerNombre"
                autocomplete="off"
                value="{{ old('primerNombre', $participante->getPrimerNombre()) }}" placeholder="Primer nombre">
                <label class="form-label text-gray-dark fw-normal fs-sm" for="primerNombre">Primer nombre</label>
                @error('primerNombre')
                <span class="invalid-feedback fs-sm" role="alert">
                    {{ $message }}
                </span>
                @enderror
        </div>

        <br>    
        <div class="form-floating">
        <input type="text" 
                class="form-control fs-sm @error('primerApellido') is-invalid @enderror" 
                id="primerApellido" 
                name="primerApellido"
                autocomplete="off"
                value="{{ old('primerApellido', $participante->getPrimerApellido()) }}" placeholder="Primer apellido">                                   
                <label class="form-label text-gray-dark fw-normal fs-sm" for="primerApellido">Primer apellido</label>
                @error('primerApellido')
                <span class="invalid-feedback fs-sm" role="alert">
                    {{ $message }}
                </span>
                @enderror
        </div>

        <br>    
        <div class="form-floating">
        <select name="tipoDocumento" id="tipoDocumento" class="form-control fs-sm @error('tipoDocumento') is-invalid @enderror">
                <option value=""> - Seleccione una opción - </option>
                <option value="CC" {{ old('tipoDocumento', $participante->getTipoDocumento()) == "CC" ? 'selected' : '' }} selected>Cédula de ciudadanía</option>
                <option value="TI" {{ old('tipoDocumento', $participante->getTipoDocumento()) == "TI" ? 'selected' : '' }}>Tarjeta de identidad</option>
                <option value="CE" {{ old('tipoDocumento', $participante->getTipoDocumento()) == "CE" ? 'selected' : '' }}>Cédula de extranjería</option>
                <option value="PP" {{ old('tipoDocumento', $participante->getTipoDocumento()) == "PP" ? 'selected' : '' }}>Pasaporte</option>
            </select>
            <label class="form-label text-gray-dark fw-normal fs-sm" for="tipoDocumento">Tipo documento</label>          
            @error('tipoDocumento')
             <span class="invalid-feedback fs-sm" role="alert">
                {{ $message }}
             </span>
            @enderror
        </div>   
        
        <br>    
        <div class="form-floating">
        <input type="text" 
                class="js-flatpickr form-control fs-sm @error('fecNacimiento') is-invalid @enderror" 
                id="fecNacimiento" 
                name="fecNacimiento"
                autocomplete="off"
                value="{{ old('fecNacimiento', $participante->getFechaNacimiento()) }}" placeholder="Fecha de nacimiento">                                   
                <label class="form-label text-gray-dark fw-normal fs-sm" for="fecNacimiento">Fecha de nacimiento</label>
                @error('fecNacimiento')
                <span class="invalid-feedback fs-sm" role="alert">
                        {{ $message }}
                </span>
                @enderror                
        </div>   
        
        <br>    
        <div class="form-floating">
        <input type="text" 
                class="form-control fs-sm @error('direccion') is-invalid @enderror" 
                id="direccion" 
                name="direccion"
                autocomplete="off"
                value="{{ old('direccion', $participante->getDireccion()) }}" placeholder="Dirección">                                   
                <label class="form-label text-gray-dark fw-normal fs-sm" for="direccion">Dirección</label>
                @error('direccion')
                <span class="invalid-feedback fs-sm" role="alert">
                        {{ $message }}
                </span>
                @enderror                
        </div>  
        
        <br>    
        <div class="form-floating">
        <input type="text" 
                class="form-control fs-sm @error('telefono') is-invalid @enderror" 
                id="telefono" 
                name="telefono"
                autocomplete="off"
                value="{{ old('telefono', $participante->getTelefono()) }}" placeholder="Teléfono">                                   
                <label class="form-label text-gray-dark fw-normal fs-sm" for="telefono">Teléfono</label>
                @error('telefono')
                <span class="invalid-feedback fs-sm" role="alert">
                        {{ $message }}
                </span>
                @enderror
        </div> 
        
        <br>    
        <div class="form-floating">
        <select name="eps" class="form-control fs-sm @error('eps') is-invalid @enderror" id="eps">
                <option value="" selected> - Seleccione una opción - </option>
                @foreach ($listaEPS as $nombreEPS)
                    <option value="{{ $nombreEPS }}" {{ old('eps', $participante->getEps()) == $nombreEPS ? 'selected' : '' }}>{{ $nombreEPS }}</option>    
                @endforeach
            </select>
            <label class="form-label text-gray-dark fw-normal fs-sm" for="eps">EPS</label>
            @error('eps')
            <span class="invalid-feedback fs-sm" role="alert">
                {{ $message }}
            </span>
            @enderror
        </div> 
        
        <br>
        <div class="form-floating">
        <input type="text" 
                class="form-control fs-sm @error('telefonoEmergencia') is-invalid @enderror" 
                id="telefonoEmergencia" 
                name="telefonoEmergencia"
                autocomplete="off"
                value="{{ old('telefonoEmergencia', $participante->getTelefonoEmergencia()) }}" placeholder="Número de teléfono del contacto de emergencia">                                   
                <label class="form-label text-gray-dark fw-normal fs-sm" for="telefonoEmergencia">Número de teléfono del contacto de emergencia</label>                          
                @error('telefonoEmergencia')
                <span class="invalid-feedback fs-sm" role="alert">
                        {{ $message }}
                </span>
                @enderror                
        </div>            

    </div>

    <div class="col-6">
    
        <div class="form-floating">
        <input type="text" 
                class="form-control fs-sm" 
                id="segundoNombre" 
                name="segundoNombre"
                autocomplete="off"
                value="{{ old('segundoNombre', $participante->getSegundoNombre()) }}" placeholder="Segundo nombre">                                   
                <label class="form-label text-gray-dark fw-normal fs-sm" for="segundoNombre">Segundo nombre</label>                         
        </div> 

        <br>
        <div class="form-floating">
        <input type="text" 
                class="form-control fs-sm" 
                id="segundoApellido" 
                name="segundoApellido"
                autocomplete="off"
                value="{{ old('segundoApellido', $participante->getSegundoApellido()) }}" placeholder="Segundo apellido">                                   
                <label class="form-label text-gray-dark fw-normal fs-sm" for="segundoApellido">Segundo apellido</label>                         
        </div>     
        
        <br>
        <div class="form-floating">
        <input type="text" 
                class="form-control fs-sm @error('documento') is-invalid @enderror" 
                id="documento" 
                name="documento"
                autocomplete="off"
                value="{{ old('documento', $participante->getDocumento()) }}" placeholder="Número de documento">                                   
                <label class="form-label text-gray-dark fw-normal fs-sm" for="documento">Número de documento</label>
                @error('documento')
                <span class="invalid-feedback fs-sm" role="alert">
                        {{ $message }}
                </span>
                @enderror                
        </div>             

        <br>    
        <div class="form-floating">
        <select name="sexo" id="sexo" class="form-control fs-sm @error('sexo') is-invalid @enderror">
                <option value="" selected> - Seleccione una opción - </option>
                @foreach ($listaSexo as $sexo)                    
                    <option value="{{ $sexo['value'] }}" {{ old('sexo', $participante->getSexo()) == $sexo['value'] ? 'selected' : '' }}>{{ $sexo['nombre'] }}</option>
                @endforeach                
            </select>
            <label class="form-label text-gray-dark fw-normal fs-sm" for="sexo">Género</label>
            @error('sexo')
                <span class="invalid-feedback fs-sm" role="alert">
                        {{ $message }}
                </span>
            @enderror               
        </div> 
        
        <br>    
        <div class="form-floating">
        <select name="estadoCivil" id="estadoCivil" class="form-control fs-sm @error('estadoCivil') is-invalid @enderror">
                <option value="" selected> - Seleccione una opción - </option>
                @foreach ($estadosCiviles as $estadoCivil)                    
                    <option value="{{ $estadoCivil['value'] }}" {{ old('estadoCivil', $participante->getEstadoCivil()) == $estadoCivil['value'] ? 'selected' : '' }}>{{ $estadoCivil['nombre'] }}</option>
                @endforeach
                
            </select>
            <label class="form-label text-gray-dark fw-normal fs-sm" for="estadoCivil">Estado civil</label>
            @error('estadoCivil')
                <span class="invalid-feedback fs-sm" role="alert">
                        {{ $message }}
                </span>
            @enderror                           
        </div>      
        
        <br>    
        <div class="form-floating">
        <input type="email" 
                class="form-control fs-sm @error('email') is-invalid @enderror" 
                id="email" 
                name="email"
                autocomplete="off"
                value="{{ old('email', $participante->getEmail()) }}" placeholder="Correo electrónico">                                   
                <label class="form-label text-gray-dark fw-normal fs-sm" for="email">Correo electrónico</label>
                @error('email')
                <span class="invalid-feedback fs-sm" role="alert">
                        {{ $message }}
                </span>
                @enderror
        </div> 

        <br>
        <div class="form-floating">
        <input type="text" 
                class="form-control fs-sm @error('contactoEmergencia') is-invalid @enderror" 
                id="contactoEmergencia" 
                name="contactoEmergencia"
                autocomplete="off"
                value="{{ old('contactoEmergencia', $participante->getContactoEmergencia()) }}" placeholder="Nombre del contacto de emergencia">                                   
                <label class="form-label text-gray-dark fw-normal fs-sm" for="contactoEmergencia">Nombre del contacto de emergencia</label>
                @error('contactoEmergencia')
                <span class="invalid-feedback fs-sm" role="alert">
                        {{ $message }}
                </span>
                @enderror                
        </div> 
        
        <!-- <br><br>
        <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="vinculadoUnicolMayor" name="vinculadoUnicolMayor" {{ $checked }}>
                <label class="form-check-label" for="disponible">¿Tiene vínculo laboral con la Universidad?</label>
        </div> -->

    </div> 
    

</div>

<script src="{{asset('assets/js/oneui.app.min.js')}}"></script>
<script src="{{asset('assets/js/lib/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/flatpickr/flatpickr.min.js')}}"></script>
<script>One.helpersOnLoad(['js-flatpickr']);</script>