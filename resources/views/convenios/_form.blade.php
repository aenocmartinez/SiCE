@php
    $checked = '';
    if ($convenio->existe()) {
        $checked = $convenio->esCooperativa() ? 'checked' : '';
    }
@endphp

<div class="block block-rounded">

    <div class="block-content">

        <div class="row push">

            <div class="col-6">

                <label class="form-label" for="nombre">Nombre</label>
                <input type="text" 
                    class="form-control @error('nombre') is-invalid @enderror" 
                    id="nombre" 
                    name="nombre" 
                    placeholder="Nombre" 
                    value="{{ old('nombre', $convenio->getNombre()) }}"
                    >
                    @error('nombre')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                <br>

                <label class="form-label" for="calendario">Periodo</label>
                <input type="hidden" name="calendario" value="{{ $convenio->getCalendarioId() }}">
                <input type="text" 
                       class="form-control" 
                       name="periodo" 
                       id="periodo" 
                       disabled
                       value="{{ $convenio->existeCalendarioVigente() ? $convenio->getNombreCalendario() : 'No existe periodo académico vigente' }}">

                <br>                      

                <label class="form-label" for="fec_ini">Fecha inicial</label>
                <input type="text" 
                       class="js-flatpickr form-control @error('fec_ini') is-invalid @enderror" 
                       id="fec_ini" 
                       name="fec_ini" 
                       placeholder="Y-m-d"
                       value="{{ old('fec_ini', $convenio->getFecInicio()) }}"
                       >
                    @error('fec_ini')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror            

                    <br>
                <label class="form-label" for="fec_fin">Fecha final</label>
                <input type="text" 
                       class="js-flatpickr form-control @error('fec_fin') is-invalid @enderror" 
                       id="fec_fin" 
                       name="fec_fin" 
                       placeholder="Y-m-d"
                       value="{{ old('fec_fin', $convenio->getFecFin()) }}"
                       >
                    @error('fec_fin')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
            </div>

            <!-- Columna 2 -->
            <div class="col-6">
                <label class="form-label" for="comentarios">Comentarios</label>
                <textarea class="form-control fs-xs" id="comentarios" name="comentarios" style="height: 130px">{{ old('comentarios', $convenio->getComentarios()) }}</textarea>
                <br>           

                <label class="form-label" for="descuento">Descuento</label>
                <input type="number" min="0" step="1"
                    class="form-control @error('descuento') is-invalid @enderror" 
                    id="descuento" 
                    name="descuento" 
                    placeholder="descuento" 
                    value="{{ old('descuento', $convenio->getDescuento()) }}"
                    >
                    @error('descuento')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror  
                        
                    <br>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="esCooperativa" name="esCooperativa" {{ $checked }}>
                        <label class="form-check-label" for="disponible">Es una cooperativa</label>
                    </div> 
            </div>
            
            <div class="col-12 mt-4">
                @if (!$convenio->haSidoFacturado())
                <button class="btn btn-large btn-info">{{ $btnText }}</button>        
                @endif
                <a href="{{ route('convenios.index') }}" class="btn btn-large btn-light"> Cancelar</a>
            </div>            

        </div>
    
    </div>

</div>    


<script src="{{asset('assets/js/oneui.app.min.js')}}"></script>

<script src="{{asset('assets/js/lib/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/flatpickr/flatpickr.min.js')}}"></script>


<script>One.helpersOnLoad(['js-flatpickr']);</script>