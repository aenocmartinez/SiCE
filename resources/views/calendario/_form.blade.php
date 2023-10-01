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
                    value="{{ old('nombre', $calendario->getNombre()) }}"
                    >
                    @error('nombre')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror

                <label class="form-label mt-2" for="fec_ini">Fecha inicial</label>
                <input type="text" 
                       class="js-flatpickr form-control @error('fec_ini') is-invalid @enderror" 
                       id="fec_ini" 
                       name="fec_ini" 
                       placeholder="Y-m-d"
                       value="{{ old('fec_ini', $calendario->getFechaInicio()) }}"
                       >
                    @error('fec_ini')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror            

                <label class="form-label mt-2" for="fec_fin">Fecha final</label>
                <input type="text" 
                       class="js-flatpickr form-control @error('fec_fin') is-invalid @enderror" 
                       id="fec_fin" 
                       name="fec_fin" 
                       placeholder="Y-m-d"
                       value="{{ old('fec_fin', $calendario->getFechaFinal()) }}"
                       >
                    @error('fec_fin')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
            </div>

            <div class="col-12 mt-4">
                <button class="btn btn-large btn-info">{{ $btnText }}</button>        
                <a href="{{ route('calendario.index') }}" class="btn btn-large btn-light"> Cancelar</a>
            </div>

        </div>
    
    </div>

</div>    


<script src="{{asset('assets/js/oneui.app.min.js')}}"></script>

<script src="{{asset('assets/js/lib/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/flatpickr/flatpickr.min.js')}}"></script>


<script>One.helpersOnLoad(['js-flatpickr']);</script>