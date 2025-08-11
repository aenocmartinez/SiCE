@extends("plantillas.principal")

@php
    $titulo = "Registrar asistencia";
    $orientador = $datosFormulario['orientador'];
    $grupos = $datosFormulario['grupos'];
@endphp

@section("title", $titulo)

@section("seccion")
    <a class="link-fx" href="{{ route('dashboard') }}">
        Dashboard
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
                        <select name="grupo_id" id="grupo_id" class="form-control fs-xs" required>
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

            <div class="row mb-3" id="info-extra" style="display: none;">
                <div class="col-md-12">
                    <div class="bg-light rounded p-3">
                        <div class="row text-center fs-xs">
                            <div class="col-md-2"><strong>Grupo:</strong> <span id="info-grupo"></span></div>
                            <div class="col-md-2"><strong>Periodo:</strong> <span id="info-periodo"></span></div>
                            <div class="col-md-2"><strong>Salón:</strong> <span id="info-salon"></span></div>
                            <div class="col-md-3"><strong>Participantes:</strong> <span id="info-participantes"></span></div>
                            <div class="col-md-3"><strong>Área:</strong> <span id="info-area"></span></div>
                        </div>
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
                                    <th>Teléfono</th>
                                    <th>Convenio</th>
                                    <th>Presente</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="text-muted">Seleccione un grupo para ver los participantes.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4 mb-4">
                <button type="submit" class="btn btn-primary" id="btn-registrar-asistencia">
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
    const btnRegistrar = document.getElementById('btn-registrar-asistencia');
    btnRegistrar.style.display = 'none'; 

    selectGrupo.addEventListener('change', () => {
        const grupoID = selectGrupo.value ? parseInt(selectGrupo.value) : null;
        const grupo = grupos.find(g => g.id === grupoID);

        campoSesion.value = grupo ? grupo.proxima_sesion : '';
        tablaParticipantes.innerHTML = '';

        // Condiciones para ocultar el botón
        const grupoNoSeleccionado = !grupoID || !grupo;
        const sesionInvalida = grupo && grupo.proxima_sesion >= 17;
        const sinParticipantes = !grupo || grupo.participantes.length <= 1;

        if (grupoNoSeleccionado || sesionInvalida || sinParticipantes) {
            btnRegistrar.style.display = 'none';
        } else {
            btnRegistrar.style.display = 'inline-block';
        }

        if (grupo) {
            document.getElementById('info-extra').style.display = 'block';
            document.getElementById('info-grupo').textContent = grupo.codigo_grupo;
            document.getElementById('info-periodo').textContent = grupo.participantes[1][11] ?? 'N/A';
            document.getElementById('info-salon').textContent = grupo.nombre_salon;
            document.getElementById('info-participantes').textContent = (grupo.participantes.length - 1); 
            document.getElementById('info-area').textContent = grupo.participantes[1][14] ?? 'N/A';
        } else {
            document.getElementById('info-extra').style.display = 'none';
        }

        if (!sinParticipantes && grupo) {
            grupo.participantes.forEach((p, i) => {
                if (i === 0) return;

                const nombre = p[5];
                const documento = p[6];
                const telefono = p[7];
                const participante_id = p[15] ?? i;
                const convenio = p[12];

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="fs-xs text-start">${nombre}</td>
                    <td class="fs-xs">${documento}</td>
                    <td class="fs-xs">${telefono}</td>
                    <td class="fs-xs">${convenio}</td>
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
                    <td colspan="5" class="text-muted">Este grupo no tiene participantes.</td>
                </tr>
            `;
        }
    });
</script>

@endsection
