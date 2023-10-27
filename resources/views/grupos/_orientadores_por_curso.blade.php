<select class="form-select @error('orientador') is-invalid @enderror" id="orientador" name="orientador">
    <option value="">Selecciona un orientador</option>
    @foreach ($orientadores as $orientador)
        <option 
            value="{{ $orientador->getId() }}" 
            {{ old('orientador') }}
            {{ ($orientador->getId() == $orientadorIdActual) ? 'selected' : ''}}
            >
            {{ $orientador->getNombre() }} ({{ $orientador->getTipoNumeroDocumento() }})
        </option>
    @endforeach
</select>