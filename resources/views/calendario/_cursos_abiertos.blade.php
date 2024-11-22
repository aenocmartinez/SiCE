@foreach ($cursosCalendario as $index => $cursoCalendario)
<tr>
    <td>
        <input type="hidden" name="cursos[{{ $index }}][curso_id]" value="{{ $cursoCalendario->getCursoId() }}">
        {{ $cursoCalendario->getNombreCurso() }}
    </td>
    <td>
        <input type="text" class="form-control form-control-sm cost-input fs-xs" name="cursos[{{ $index }}][costo]" value="{{ number_format($cursoCalendario->getCosto(), 0, ',', '.') }}">
    </td>
    <td>
        <select class="form-select form-select-sm fs-xs" name="cursos[{{ $index }}][modalidad]">
            <option value="Presencial" {{ $cursoCalendario->getModalidad() == 'Presencial' ? 'selected' : '' }}>Presencial</option>
            <option value="Virtual" {{ $cursoCalendario->getModalidad() == 'Virtual' ? 'selected' : '' }}>Virtual</option>
        </select>
    </td>
    <td class="text-center">
        <button class="btn btn-danger btn-sm fw-light remove-course fs-xs">X</button>
    </td>
</tr>
@endforeach
