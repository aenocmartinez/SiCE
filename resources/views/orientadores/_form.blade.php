@php
    $selectedTD = $orientador->getTipoDocumento() != ""  ? $orientador->getTipoDocumento() : '';
@endphp

<div class="block block-rounded">
    <div class="block-content">
        <div class="row push">
            <h5 class="fw-light link-fx mb-4 text-primary-darker">INFORMACIÓN BÁSICA</h5> 

            <div class="col-sm-6">
                <label class="form-label" for="nombre">Nombre <small class="fw-light">(obligatorio)</small></label>
                <input type="text" 
                    class="form-control @error('nombre') is-invalid @enderror" 
                    id="nombre" 
                    name="nombre" 
                    placeholder="Nombre" 
                    value="{{ old('nombre', $orientador->getNombre()) }}"                
                    >
                    @error('nombre')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                <br>

                <label class="form-label" for="tipoDocumento">Tipo de documento <small class="fw-light">(obligatorio)</small></label>
                <select class="form-select @error('tipoDocumento') is-invalid @enderror" id="tipoDocumento" name="tipoDocumento">            
                    <option value=""> - </option>
                    <option value="CC" {{ old('tipoDocumento', $selectedTD) == "CC" ? 'selected' : '' }}>Cédula</option>
                    <option value="TI" {{ old('tipoDocumento', $selectedTD) == "TI" ? 'selected' : '' }}>Tarjeta de identidad</option>
                    <option value="CE" {{ old('tipoDocumento', $selectedTD) == "CE" ? 'selected' : '' }}>Cédula de extranjería</option>
                    <option value="PP" {{ old('tipoDocumento', $selectedTD) == "PP" ? 'selected' : '' }}>Pasaporte</option>
                </select>
                @error('tipoDocumento')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror          
                <br>

                <label class="form-label" for="documento">No. documento <small class="fw-light">(obligatorio)</small></label>
                <input type="text" 
                    class="form-control @error('nombre') is-invalid @enderror"
                    id="documento" 
                    name="documento" 
                    placeholder="Documento" 
                    value="{{ old('documento', $orientador->getDocumento()) }}"                
                    >
                    @error('documento')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror 
                    <br>

                    <label class="form-label" for="fecNacimiento">Fecha de nacimiento</label>
                    <input type="text" 
                       class="js-flatpickr form-control @error('fecNacimiento') is-invalid @enderror" 
                       id="fecNacimiento" 
                       name="fecNacimiento" 
                       placeholder="Y-m-d"
                       value="{{ old('fecNacimiento', $orientador->getFechaNacimiento()) }}"
                       >
                    @error('fecNacimiento')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror

                    <br>

                    <label class="form-label" for="eps">EPS</label>
                    <select class="form-select" id="eps" name="eps">
                    <option value="">Selecciona una eps</option>
                        @foreach ($listaEps as $eps)            
                            <option value="{{ $eps }}" {{ old('eps', $orientador->getEps()) == $eps ? 'selected' : '' }}>{{ $eps }}</option>
                        @endforeach
                    </select>  
                    
                    <br>
                    <label class="form-label" for="areas">Áreas a las que pertenece</label>
                    <select class="js-select2 form-select @error('areas') is-invalid @enderror" 
                            id="areas" 
                            name="areas[]" 
                            style="width: 100%;" 
                            data-placeholder="Selecciones las áreas a las que pertenece" multiple>
                        <option></option>
                        @foreach ($areas as $area)
                            @php
                                $selected = in_array($area, $orientador->misAreas()) ? 'selected' : '';
                            @endphp
                            <option value="{{ $area->getId() }}" {{ $selected }}>{{ $area->getNombre() }}</option>    
                        @endforeach                        
                    </select>
                    @error('areas')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror                    

            </div>

            <div class="col-sm-6">

                <label class="form-label" for="nivelEstudio">Nivel de estudio</label>
                <select class="form-select" id="nivelEstudio" name="nivelEstudio">
                <option value="">Selecciona una opción</option>
                    @foreach ($nivelesEstudio as $nivel)            
                        <option value="{{ $nivel }}" {{ $orientador->getNivelEducativo() == $nivel ? 'selected' : '' }}>{{ $nivel }}</option>
                    @endforeach
                </select>  
                <br>

                <label class="form-label" for="rangoSalarial">Rango salarial</label>
                <select class="form-select" id="rangoSalarial" name="rangoSalarial">
                <option value="">Selecciona una opción</option>
                    @foreach ($listaRangoSalarial as $rango)            
                        <option value="{{ $rango }}" {{ old('rangoSalarial', $orientador->getRangoSalarial()) == $rango ? 'selected' : '' }}>{{ $rango }}</option>
                    @endforeach
                </select>  
                <br>                

                <label class="form-label" for="emailInstitucional">Correo institucional</label>
                <input type="email" 
                    class="form-control @error('emailInstitucional') is-invalid @enderror"
                    id="emailInstitucional" 
                    name="emailInstitucional" 
                    placeholder="Correo institucional" 
                    value="{{ old('emailInstitucional', $orientador->getEmailInstitucional()) }}"                
                    >
                    @error('emailInstitucional')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror 
                    <br>
                        
                <label class="form-label" for="emailPersonal">Correo personal</label>
                <input type="email" 
                    class="form-control @error('emailPersonal') is-invalid @enderror"
                    id="emailPersonal" 
                    name="emailPersonal" 
                    placeholder="Correo personal" 
                    value="{{ old('emailPersonal', $orientador->getEmailPersonal()) }}"                
                    >
                    @error('emailPersonal')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror 
                    <br>
                        
                    <label class="form-label" for="direccion">Dirección</label>
                    <input type="text" 
                    class="form-control @error('direccion') is-invalid @enderror"
                    id="direccion" 
                    name="direccion" 
                    placeholder="Dirección" 
                    value="{{ old('direccion', $orientador->getDireccion()) }}"                
                    >
                    @error('direccion')
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
            <h5 class="fw-light">PERFIL DEL ORIENTADOR</h5>   
            <div class="col-12">
                <textarea class="js-simplemde" rows="4" id="observacion" name="observacion">{{ old('observacion', $orientador->getObservacion()) }}</textarea>
            </div>

            <div class="col-12">
                <button class="btn btn-large btn-info">{{ $btnText }}</button>        
                <a href="{{ route('orientadores.index') }}" class="btn btn-large btn-light"> Cancelar</a>
            </div>
        </div>
    </div>
</div>


<script src="{{asset('assets/js/oneui.app.min.js')}}"></script>
<script src="{{asset('assets/js/lib/jquery.min.js')}}"></script>


<!-- <script src="{{asset('assets/js/plugins/select2/js/select2.full.min.js')}}"></script> -->


<script src="{{asset('assets/js/plugins/ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('assets/js/plugins/simplemde/simplemde.min.js')}}"></script>
<!-- <script>One.helpersOnLoad(['js-ckeditor', 'js-simplemde']);</script> -->


<script src="{{asset('assets/js/plugins/flatpickr/flatpickr.min.js')}}"></script>
<script>One.helpersOnLoad(['js-flatpickr', 'jq-select2', 'js-ckeditor', 'js-simplemde']);</script>
