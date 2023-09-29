@php
    $selectedTD = $orientador['tipoDocumento'] != ""  ? $orientador['tipoDocumento'] : '';
@endphp

<div class="row push">
    <div class="col-sm-6">
    <div class="mb-4">
        <label class="form-label" for="nombre">Nombre</label>
        <input type="text" 
            class="form-control @error('nombre') is-invalid @enderror" 
            id="nombre" 
            name="nombre" 
            placeholder="Nombre" 
            value="{{ old('nombre', $orientador['nombre']) }}"                
            >
            @error('nombre')
                <span class="invalid-feedback" role="alert">
                    {{ $message }}
                </span>
            @enderror
        <br>

        <label class="form-label" for="tipoDocumento">Tipo de documento</label>
        <select class="form-select @error('tipoDocumento') is-invalid @enderror" id="tipoDocumento" name="tipoDocumento">            
            <option value=""> - </option>
            <option value="CC" {{ $selectedTD == "CC" ? 'selected' : '' }}>Cédula</option>
            <option value="TI" {{ $selectedTD == "TI" ? 'selected' : '' }}>Tarjeta de identidad</option>
            <option value="CE" {{ $selectedTD == "CE" ? 'selected' : '' }}>Cédula de extranjería</option>
            <option value="PP" {{ $selectedTD == "PP" ? 'selected' : '' }}>Pasaporte</option>
        </select>
        @error('tipoDocumento')
            <span class="invalid-feedback" role="alert">
                {{ $message }}
            </span>
        @enderror          
        <br>

        <label class="form-label" for="documento">No. documento</label>
        <input type="text" 
            class="form-control @error('nombre') is-invalid @enderror"
            id="documento" 
            name="documento" 
            placeholder="Documento" 
            value="{{ old('documento', $orientador['documento']) }}"                
            >
            @error('documento')
                <span class="invalid-feedback" role="alert">
                    {{ $message }}
                </span>
            @enderror 
            <br>

            <label for="observacion">Observaciones</label>
            <div class="mb-4">
                <textarea class="js-simplemde" rows="4" id="observacion" name="observacion">{{ old('observacion', $orientador['observacion']) }}</textarea>
            </div>
    </div>
    </div>

    <!-- Segunda columna -->
    <div class="col-sm-6">
    <div class="mb-4">
        <label class="form-label" for="emailInstitucional">Correo institucional</label>
        <input type="text" 
            class="form-control @error('emailInstitucional') is-invalid @enderror"
            id="emailInstitucional" 
            name="emailInstitucional" 
            placeholder="@e-mail" 
            value="{{ old('emailInstitucional', $orientador['emailInstitucional']) }}"                
            >
            @error('emailInstitucional')
                <span class="invalid-feedback" role="alert">
                    {{ $message }}
                </span>
            @enderror 
            <br> 
            
        <label class="form-label" for="emailPersonal">Correo personal</label>
        <input type="text" 
            class="form-control @error('emailPersonal') is-invalid @enderror"
            id="emailPersonal" 
            name="emailPersonal" 
            placeholder="@e-mail" 
            value="{{ old('emailPersonal', $orientador['emailPersonal']) }}"                
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
            value="{{ old('direccion', $orientador['direccion']) }}"                
            >
            @error('direccion')
                <span class="invalid-feedback" role="alert">
                    {{ $message }}
                </span>
            @enderror 
            <br>    
            
            <label class="form-label" for="eps">EPS</label>
            <select class="form-select @error('eps') is-invalid @enderror" id="eps" name="eps">
            <option value="">Selecciona una eps</option>
                @foreach ($orientador['listaEps'] as $eps)            
                    <option value="{{ $eps }}" {{ $orientador['eps'] == $eps ? 'selected' : '' }}>{{ $eps }}</option>
                @endforeach
            </select>                
    </div>

    <button class="btn btn-large btn-info">{{ $btnText }}</button>        
    <a href="{{ route('orientadores.index') }}" class="btn btn-large btn-light"> Cancelar</a>
    </div>

</div>