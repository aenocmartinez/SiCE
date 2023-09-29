@php
    $areaId = isset($curso['areaId']) ? $curso['areaId'] : 0;
    $checked = isset($curso['modalidad']) && $curso['modalidad'] == 'virtual' ? 'checked' : '';
@endphp

<div class="row push">
    <div class="col-lg-8 col-xl-5">

    <div class="mb-4">
        <label class="form-label" for="nombre">Nombre</label>
        <input type="text" 
            class="form-control @error('nombre') is-invalid @enderror" 
            id="nombre" 
            name="nombre" 
            placeholder="Nombre" 
            value="{{ old('nombre', $curso['nombre']) }}"                
            >
            @error('nombre')
                <span class="invalid-feedback" role="alert">
                    {{ $message }}
                </span>
            @enderror

        <br>
        <label class="form-label" for="area">Área</label>
        <select class="form-select @error('area') is-invalid @enderror" id="area" name="area">
            <option value="">Selecciona un área</option>
            @foreach ($curso['areas'] as $area)
                <option value="{{ $area['id'] }}" {{ $areaId == $area['id'] ? 'selected' : '' }} >{{ $area['nombre'] }}</option>
            @endforeach
        </select>
        @error('area')
            <span class="invalid-feedback" role="alert">
                {{ $message }}
            </span>
        @enderror   
        
        <br>
        <label class="form-label" for="costo">Costo</label>
        <input type="text" 
            class="form-control @error('costo') is-invalid @enderror" 
            id="costo" 
            name="costo" 
            placeholder="Costo" 
            value="{{ old('costo', $curso['costo']) }}"                
            >
            @error('nombre')
                <span class="invalid-feedback" role="alert">
                    {{ $message }}
                </span>
            @enderror

            <br>
            
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="modalidad" name="modalidad" {{ $checked }}>
                <label class="form-check-label" for="modalidad">Modalidad virtual</label>
            </div>

    </div>
        
        <button class="btn btn-large btn-info">{{ $btnText }}</button>        
        <a href="{{ route('cursos.index') }}" class="btn btn-large btn-light"> Cancelar</a>

    </div>
</div>