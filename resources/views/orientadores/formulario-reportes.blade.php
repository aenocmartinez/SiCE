@extends("plantillas.principal")

@php
    $titulo = "Consultar asistencia por sesión";
@endphp

@section("title", $titulo)

@section("seccion")
    <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
@endsection

@section("subseccion", $titulo)

@section("content")
<div class="block block-rounded">
    <div class="block-content">
        <div class="row push">
            <div class="col-md-4">
                <label for="periodo" class="form-label fw-semibold text-center d-block">Periodo</label>
                <select id="periodo" class="form-control fs-xs">
                    <option value="">Seleccionar periodo</option>
                    @foreach (array_keys($datos) as $periodo)
                        <option value="{{ $periodo }}">{{ $periodo }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="grupo_id" class="form-label fw-semibold text-center d-block">Grupo</label>
                <select id="grupo_id" class="form-control fs-xs" disabled>
                    <option value="">Seleccione primero un periodo</option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="sesion" class="form-label fw-semibold text-center d-block">Sesión</label>
                <select id="sesion" class="form-control fs-xs" disabled>
                    <option value="">Seleccione grupo</option>
                </select>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="row mb-3" id="info-extra" style="display: none;">
            <div class="col-md-12">
                <div class="bg-light rounded p-3 fs-xs">
                    <div class="row text-center">
                        <div class="col-md-2"><strong>Curso:</strong> <span id="info-curso"></span></div>
                        <div class="col-md-2"><strong>Jornada:</strong> <span id="info-jornada"></span></div>
                        <div class="col-md-2"><strong>Salón:</strong> <span id="info-salon"></span></div>
                        <div class="col-md-2"><strong>Día:</strong> <span id="info-dia"></span></div>
                        <div class="col-md-2"><strong>Área:</strong> <span id="info-area"></span></div>
                        <div class="col-md-2"><strong>Registrado el:</strong> <span id="info-fecha-registro"></span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered text-center fs-xs" id="tabla-asistencia">
                        <thead>
                            <tr class="bg-body-dark">
                                <th>Nombre</th>
                                <th>Documento</th>
                                <th>Convenio</th>
                                <th>Presente</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-muted">Seleccione un grupo y sesión.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    const datos = @json($datos);

    const periodoSelect = document.getElementById('periodo');
    const grupoSelect = document.getElementById('grupo_id');
    const sesionSelect = document.getElementById('sesion');
    const tabla = document.getElementById('tabla-asistencia').querySelector('tbody');

    periodoSelect.addEventListener('change', () => {
        const periodo = periodoSelect.value;
        grupoSelect.innerHTML = '<option value="">Seleccionar grupo</option>';
        grupoSelect.disabled = true;
        sesionSelect.innerHTML = '<option value="">Seleccionar sesión</option>';
        sesionSelect.disabled = true;
        tabla.innerHTML = '<tr><td colspan="4" class="text-muted">Seleccione un grupo y sesión.</td></tr>';
        document.getElementById('info-extra').style.display = 'none';

        if (!periodo || !datos[periodo]) return;

        datos[periodo].forEach(g => {
            grupoSelect.innerHTML += `<option value="${g.id}">${g.nombre_curso} (${g.dia} - ${g.jornada})</option>`;
        });

        grupoSelect.disabled = false;
    });

    grupoSelect.addEventListener('change', () => {
        const periodo = periodoSelect.value;
        const grupoID = parseInt(grupoSelect.value);
        const grupo = datos[periodo]?.find(g => g.id === grupoID);

        sesionSelect.innerHTML = '<option value="">Seleccionar sesión</option>';
        tabla.innerHTML = '<tr><td colspan="4" class="text-muted">Seleccione una sesión.</td></tr>';
        document.getElementById('info-extra').style.display = 'none';

        if (!grupo || !grupo.sesiones) return;

        Object.entries(grupo.sesiones).forEach(([num, sesion]) => {
            sesionSelect.innerHTML += `<option value="${num}">Sesión ${num}</option>`;
        });

        sesionSelect.disabled = false;
    });

    sesionSelect.addEventListener('change', () => {
        const periodo = periodoSelect.value;
        const grupoID = parseInt(grupoSelect.value);
        const sesion = sesionSelect.value;
        const grupo = datos[periodo]?.find(g => g.id === grupoID);
        const sesionData = grupo?.sesiones?.[sesion];

        if (!grupo || !sesionData) return;

        document.getElementById('info-extra').style.display = 'block';
        document.getElementById('info-curso').textContent = grupo.nombre_curso;
        document.getElementById('info-jornada').textContent = grupo.jornada;
        document.getElementById('info-salon').textContent = grupo.salon;
        document.getElementById('info-dia').textContent = grupo.dia;
        document.getElementById('info-area').textContent = grupo.area;
        document.getElementById('info-fecha-registro').textContent = sesionData.fecha;

        tabla.innerHTML = '';
        sesionData.participantes.forEach(p => {
            tabla.innerHTML += `
                <tr>
                    <td class="text-start">${p.nombre}</td>
                    <td>${p.doc}</td>
                    <td>${p.convenio}</td>
                    <td>${p.presente ? '✔️' : '❌'}</td>
                </tr>
            `;
        });
    });
</script>
@endsection
