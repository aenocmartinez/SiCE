<div class="block block-rounded">
    <div class="block-content">

        <div class="row push">
            <div class="col-lg-8 col-xl-5">

            <div class="mb-4">
                <label class="form-label" for="nombre">Nombre</label>
                <input type="text" 
                    class="form-control @error('nombre') is-invalid @enderror" 
                    id="nombre" 
                    name="nombre" 
                    placeholder="Nombre" 
                    value="{{ old('nombre', $tipo->getNombre()) }}"                
                    >
                    @error('nombre')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror

                <br>    

                <button class="btn btn-large btn-info">{{ $btnText }}</button>        
                <a href="{{ route('tipo-salones.index',1) }}" class="btn btn-large btn-light"> Cancelar</a>

            </div>
        </div>

    </div>
</div>