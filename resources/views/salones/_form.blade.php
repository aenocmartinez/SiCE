
@php
    $checked = 'checked';
    if ($salon->existe()) {
        $checked = $salon->estaDisponible() ? 'checked' : '';
    }
    
@endphp

<div class="block block-rounded">
    
    <div class="block-content">
        
        <div class="row push">

            <div class="col-6">

                <div class="mb-4">

                    <label class="form-label" for="nombre">Número</label>
                    <input type="number" min="1" step="1" 
                        class="form-control @error('nombre') is-invalid @enderror" 
                        id="nombre" 
                        name="nombre" 
                        placeholder="Número" 
                        value="{{ old('nombre', $salon->getNombre()) }}"                
                        >
                        @error('nombre')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    
                    <br>

                    <label class="form-label" for="capacidad">Capacidad</label>
                    <input type="number" min="1" step="1"
                        class="form-control @error('capacidad') is-invalid @enderror" 
                        id="capacidad" 
                        name="capacidad" 
                        placeholder="Capacidad" 
                        value="{{ old('capacidad', $salon->getCapacidad()) }}"                
                        >
                        @error('capacidad')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                        
                    <br> 
                    
                    <label class="form-label" for="tipo_salon_id">Tipo de salón <small class="fw-light">(obligatorio)</small></label>
                    <select class="form-select @error('tipo_salon_id') is-invalid @enderror" id="tipo_salon_id" name="tipo_salon_id">            
                        <option value=""> - </option>
                        @foreach ($tiposSalon as $ts)                            
                            <option value="{{ $ts->getId() }}" {{ old('tipo_salon_id', $salon->getIdTipoSalon()) == $ts->getId() ? 'selected' : '' }}>{{ $ts->getNombre() }}</option>
                        @endforeach
                    </select>
                    @error('tipo_salon_id')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                    
                    <br>
                
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="disponible" name="disponible" {{ $checked }}>
                        <label class="form-check-label" for="disponible">Disponible</label>
                    </div>  

                    <br>    

                    <button class="btn btn-large btn-info">{{ $btnText }}</button>        
                    <a href="{{ route('salones.index', 1) }}" class="btn btn-large btn-light"> Cancelar</a>


                </div>
        
            </div>

            <!-- <div class="col-6">
                <h5 class="fw-light">HOJA DE VIDA DEL SALÓN</h5>
                <textarea class="js-simplemde" rows="4" id="hoja_vida" name="hoja_vida">{{ old('hoja_vida', $salon->getHojaVida()) }}</textarea>
            </div> -->

        </div>

    </div>

</div>

<script src="{{asset('assets/js/oneui.app.min.js')}}"></script>
<!-- <script src="{{asset('assets/js/plugins/ckeditor/ckeditor.js')}}"></script> -->
<script src="{{asset('assets/js/plugins/simplemde/simplemde.min.js')}}"></script>
<script>One.helpersOnLoad(['js-simplemde']);</script>