<div class="block block-rounded">
    <div class="block-content">
        <div class="row push">
            <!-- <h5 class="fw-light link-fx mb-4 text-primary-darker">INFORMACIÓN BÁSICA</h5>  -->


            <div class="col-12 col-lg-5 col-sm-12 mb-4">
                <label class="form-label" for="nombre">Nombre</label>
                <input type="text" 
                        class="form-control @error('nombre') is-invalid @enderror" 
                        id="nombre" 
                        name="nombre" 
                        placeholder="Nombre" 
                        value="{{ old('nombre', $area['nombre']) }}"                
                        >
                        @error('nombre')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
            </div>
                
            <div class="col-12">
                <button class="btn btn-large btn-info">{{ $btnText }}</button>        
                <a href="{{ route('areas.index') }}" class="btn btn-large btn-light"> Cancelar</a>
            </div>

        </div>
    </div>
</div>   
