@extends("plantillas.principal")

@php
    $titulo = "Consultar asistencia por curso";
    $periodos = array_keys($datos); // ["2025-1", "2024-2"]
@endphp

@section("title", $titulo)

@section("seccion")
    <a class="link-fx" href="{{ route('dashboard') }}">Dashboard</a>
@endsection

@section("subseccion", $titulo)

@section("content")
<div class="block block-rounded">
    <div class="block-content">
        <div class="row push mb-4">
            <!-- Periodo -->
            <div class="col-md-4">
                <label for="periodo" class="form-label fw-semibold text-center d-block">Periodo</label>
                <select id="periodo" class="form-control fs-xs">
                    <option value="">Seleccionar periodo</option>
                    @foreach ($periodos as $p)
                        <option value="{{ $p }}">{{ $p }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Grupo -->
            <div class="col-md-8">
                <label for="grupo_id" class="form-label fw-semibold text-center d-block">Grupo (Curso)</label>
                <select id="grupo_id" class="form-control fs-xs" disabled>
                    <option value="">Seleccione primero un periodo</option>
                </select>
            </div>
        </div>

        <div id="contenedor-grupo">
            <p class="text-muted">Seleccione un periodo y grupo para ver la asistencia.</p>
        </div>
    </div>
</div>

<script>
    const datos = @json($datos);
    const selectPeriodo = document.getElementById('periodo');
    const selectGrupo = document.getElementById('grupo_id');
    const contenedor = document.getElementById('contenedor-grupo');

    selectPeriodo.addEventListener('change', () => {
        const periodo = selectPeriodo.value;
        selectGrupo.innerHTML = `<option value="">Seleccionar grupo</option>`;
        contenedor.innerHTML = `<p class="text-muted">Seleccione un grupo para ver la asistencia.</p>`;

        if (!periodo || !datos[periodo]) {
            selectGrupo.disabled = true;
            return;
        }

        datos[periodo].forEach(g => {
            const label = `${g.nombre_curso} (${g.codigo_grupo}) - ${g.dia}, ${g.jornada}`;
            selectGrupo.innerHTML += `<option value="${g.id}">${label}</option>`;
        });

        selectGrupo.disabled = false;
    });

    selectGrupo.addEventListener('change', () => {
        const periodo = selectPeriodo.value;
        const grupoID = parseInt(selectGrupo.value);
        const grupo = datos[periodo]?.find(g => g.id === grupoID);
        contenedor.innerHTML = '';

        if (!grupo) {
            contenedor.innerHTML = `<p class="text-muted">Seleccione un grupo válido.</p>`;
            return;
        }

        if (!grupo.sesiones || Object.keys(grupo.sesiones).length === 0) {
            contenedor.innerHTML = `
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <i class="fa fa-info-circle me-2"></i>
                    <div>No hay registros de asistencia disponibles para este grupo.</div>
                </div>`;
            return;
        }

        // Render encabezado
        const encabezado = `
            <div class="bg-body-light rounded p-3 mb-2 fs-sm">
                <strong>${grupo.nombre_curso}</strong> (${grupo.codigo_grupo}) - 
                ${grupo.jornada}, ${grupo.dia} - Salón: ${grupo.salon} <br>
                Área: ${grupo.area}
            </div>`;

        let tabla = `
            <div class="table-responsive">
                <table class="table table-bordered fs-xs text-center">
                    <thead class="bg-body-dark">
                        <tr>
                            <th>Nombre</th>
                            <th>Documento</th>
                            <th>Convenio</th>`;

        const sesiones = grupo.sesiones;
        for (const [n, s] of Object.entries(sesiones)) {
            tabla += `<th>Sesión ${n}<br><span class="text-muted">${s.fecha}</span></th>`;
        }

        tabla += `</tr></thead><tbody>`;

        const participantesMap = new Map();
        for (const s of Object.values(sesiones)) {
            for (const p of s.participantes) {
                participantesMap.set(p.doc, {
                    nombre: p.nombre,
                    doc: p.doc,
                    convenio: p.convenio
                });
            }
        }

        for (const [doc, p] of participantesMap.entries()) {
            tabla += `<tr>
                <td class="text-start">${p.nombre}</td>
                <td>${p.doc}</td>
                <td>${p.convenio}</td>`;

            for (const s of Object.values(sesiones)) {
                const asistio = s.participantes.find(pp => pp.doc === doc)?.presente ?? false;
                tabla += `<td>${asistio ? '✔️' : '❌'}</td>`;
            }

            tabla += `</tr>`;
        }

        tabla += `</tbody></table></div>`;

        contenedor.innerHTML = encabezado + tabla;
    });
</script>

@endsection
