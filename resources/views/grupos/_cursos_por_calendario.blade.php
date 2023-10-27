<select class="form-select @error('curso') is-invalid @enderror" id="curso" name="curso">
    <option value="">Selecciona un curso</option>
    @foreach ($cursos as $cursoCalendario)
        <option 
            value="{{ $cursoCalendario->getId() }}" 
            {{ old('curso') }}
            {{ ( $cursoCalendario->getId() == $cursoCalendarioIdActual ? 'selected' : '') }}
            >
            {{ $cursoCalendario->getNombreCurso() }} ({{ $cursoCalendario->getModalidad() }})
        </option>
    @endforeach
</select>