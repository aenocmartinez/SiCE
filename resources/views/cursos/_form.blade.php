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
                    value="{{ old('nombre', $curso->getNombre()) }}"                
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
                    @foreach ($areas as $area)
                        <option value="{{ $area->getId() }}" {{ old('area', $curso->getAreaId()) == $area->getId() ? 'selected' : '' }} >{{ $area->getNombre() }}</option>
                    @endforeach
                </select>
                @error('area')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror   

                <br>
                
                <label class="form-label" for="tipoCurso">Tipo curso</label>
                <select class="form-select @error('tipo_curso') is-invalid @enderror" id="tipoCurso" name="tipoCurso">
                    <option value="">Selecciona un tipo</option>
                    @foreach ($tipoCursos as $tipoCurso)
                        <option value="{{ $tipoCurso }}" {{ old('tipoCurso', $curso->getTipoCurso()) == $tipoCurso ? 'selected' : '' }} >{{ $tipoCurso }}</option>
                    @endforeach
                </select>
                @error('tipoCurso')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror                  

            </div>
                
                <button class="btn btn-large btn-info">{{ $btnText }}</button>        
                <a href="{{ route('cursos.index', '1') }}" class="btn btn-large btn-light"> Cancelar</a>

            </div>
        </div>

    </div>
</div>