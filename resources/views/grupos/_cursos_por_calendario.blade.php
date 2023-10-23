<select class="form-select @error('curso') is-invalid @enderror" id="curso" name="curso">
    <option value="">Selecciona un curso</option>
    @foreach ($cursos as $curso)
        <option 
            value="{{ $curso->getId() }}" 
            {{ old('curso') }}
            >
            {{ $curso->getNombreCurso() }} ({{ $curso->getModalidad() }})
        </option>
    @endforeach
</select>