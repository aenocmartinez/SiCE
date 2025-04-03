@extends("plantillas.principal")

@php
    $titulo = "Registrar asistencia";
    $orientador = $datosFormulario['orientador'];
    $grupos = $datosFormulario['grupos'];
@endphp

@section("title", $titulo)

@section("seccion")
    <a class="link-fx" href="{{ route('asistencia.formulario') }}">
        Asistencia
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")
<form method="POST" action="{{ route('asistencia.registrar') }}">
    @csrf

    <div class="block block-rounded">
        <div class="block-content">
            <div class="row push">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="grupo_id" class="form-label fw-semibold text-center d-block">Curso</label>
                        <select name="grupo_id" id="grupo_id" class="form-control" required>
                            <option value="">Seleccionar grupo</option>
                            @foreach ($grupos as $g)
                                <option value="{{ $g['id'] }}">
                                    {{ $g['nombre_curso'] }} ({{ $g['dia'] . " ". $g['jornada'] }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="sesion" class="form-label fw-semibold text-center d-block">Sesión a registrar</label>
                        <input type="number" name="sesion" id="sesion" class="form-control text-center fw-bold text-danger" readonly>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-12">
                    <!-- <h5 class="fw-semibold">Participantes</h5> -->
                    <div class="table-responsive">
                        <table class="table table-bordered text-center" id="tabla-participantes">
                            <thead>
                                <tr class="bg-body-dark fs-xs">
                                    <th>Nombre</th>
                                    <th>Documento</th>
                                    <th>Presente</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="3" class="text-muted">Seleccione un grupo para ver los participantes.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> Registrar Asistencia
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    const grupos = @json($grupos);

    const selectGrupo = document.getElementById('grupo_id');
    const campoSesion = document.getElementById('sesion');
    const tablaParticipantes = document.getElementById('tabla-participantes').querySelector('tbody');

    selectGrupo.addEventListener('change', () => {
        const grupoID = parseInt(selectGrupo.value);
        const grupo = grupos.find(g => g.id === grupoID);

        campoSesion.value = grupo ? grupo.proxima_sesion : '';
        tablaParticipantes.innerHTML = '';

        if (grupo && grupo.participantes.length > 1) {
            grupo.participantes.forEach((p, i) => {
                if (i === 0) return; // omitir encabezado

                const nombre = p[5];
                const documento = p[6];
                const participante_id = p[16] ?? i; // si no hay id real, usar índice

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="fs-xs text-start">${nombre}</td>
                    <td class="fs-xs">${documento}</td>
                    <td class="text-center">
                        <input type="hidden" name="asistencias[${i}][participante_id]" value="${participante_id}">
                        <input type="hidden" name="asistencias[${i}][presente]" value="0">
                        <div class="form-check form-switch d-inline-block">
                            <input class="form-check-input" type="checkbox" value="1"
                                name="asistencias[${i}][presente]" id="presente_${i}" checked>
                        </div>
                    </td>
                `;
                tablaParticipantes.appendChild(tr);
            });
        } else {
            tablaParticipantes.innerHTML = `
                <tr>
                    <td colspan="3" class="text-muted">Este grupo no tiene participantes.</td>
                </tr>
            `;
        }
    });    
</script>

@endsection
