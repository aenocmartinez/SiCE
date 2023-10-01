
@php
    $checked = 'checked';
    if (isset($salon['id'])) {
        $checked = $salon['disponible'] ? 'checked' : '';
    }
    
@endphp

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
                    value="{{ old('nombre', $salon['nombre']) }}"                
                    >
                    @error('nombre')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror

                <br>

                <div class="mb-4">
                <label class="form-label" for="capacidad">Capacidad</label>
                <input type="number" 
                    class="form-control @error('capacidad') is-invalid @enderror" 
                    id="capacidad" 
                    name="capacidad" 
                    placeholder="Capacidad" 
                    value="{{ old('capacidad', $salon['capacidad']) }}"                
                    >
                    @error('capacidad')
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
                <a href="{{ route('salones.index') }}" class="btn btn-large btn-light"> Cancelar</a>

            </div>
        </div>

    </div>
</div>