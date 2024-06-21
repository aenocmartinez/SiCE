<select class="js-select2 form-select @error('nuevo_curso') is-invalid @enderror" id="nuevo_curso" name="nuevo_curso" style="width: 100%;" data-placeholder="Selecciona un curso...">
    <option></option>
    @foreach ($grupos as $grupo)
        @if ($grupo->tieneCuposDisponibles())        
        <option value="{{ $grupo->getId().'@'.$grupo->getCosto() }}">
            {{ $grupo->getCodigoGrupo() }} - {{ $grupo->getNombreCurso()}}
            ({{ $grupo->getDia() }} / {{ $grupo->getJornada() }},  {{ $grupo->getCostoFormateado() }})
        </option>
        @endif
    @endforeach
</select>
@error('nuevo_curso')
    <span class="invalid-feedback" role="alert">
        {{ $message }}
    </span>
@enderror   