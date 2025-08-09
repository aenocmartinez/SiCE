@extends("plantillas.principal")

@php
    $titulo = "Consultar asistencia por curso";
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
                <option value="{{ $p['id'] }}">{{ $p['nombre'] }}</option>
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

{{-- Rutas para fetch (evita hardcodear) --}}
<script>
  window.Asistencia = {
    gruposUrl: "{{ route('asistencia.grupos-json') }}",                  // ?periodo_id=...
    matrizUrl: "{{ route('asistencia.participante-matriz') }}",          // ?grupo_id=...
  };
</script>

<script>
  const selectPeriodo = document.getElementById('periodo');
  const selectGrupo   = document.getElementById('grupo_id');
  const contenedor    = document.getElementById('contenedor-grupo');

  // Estado inicial
  resetSelect(selectGrupo, 'Seleccione primero un periodo', true);
  contenedor.innerHTML = `<p class="text-muted">Seleccione un periodo y grupo para ver la asistencia.</p>`;

  // 1) Al cambiar periodo → cargar grupos
  selectPeriodo.addEventListener('change', async () => {
    const periodo = selectPeriodo.value;

    resetSelect(selectGrupo, 'Seleccionar grupo', true);
    contenedor.innerHTML = `<p class="text-muted">Seleccione un grupo para ver la asistencia.</p>`;
    if (!periodo) return;

    setLoading(selectGrupo, 'Cargando grupos...');
    selectGrupo.style.cursor = 'wait';
    try {
      const url = `${window.Asistencia.gruposUrl}?periodo_id=${encodeURIComponent(periodo)}`;
      const res = await fetch(url, { credentials: 'same-origin' });
      if (!res.ok) throw new Error('No se pudieron cargar los grupos');
      const grupos = await res.json();

      resetSelect(selectGrupo, 'Seleccionar grupo');
      if (!grupos.length) {
        setError(selectGrupo, 'Sin grupos para este periodo');
        return;
      }

      grupos.forEach(g => {
        const label = `${g.nombre_curso} (${g.codigo_grupo}) - ${g.dia}, ${g.jornada}`;
        const opt = new Option(label, g.id);
        opt.dataset.meta = JSON.stringify(g);
        selectGrupo.appendChild(opt);
      });
      selectGrupo.disabled = false;
    } catch (e) {
      resetSelect(selectGrupo, 'Error cargando grupos', true);
      console.error(e);
    } finally {
      selectGrupo.style.cursor = '';
    }
  });

  // 2) Al cambiar grupo → pedir matriz y renderizar
  selectGrupo.addEventListener('change', async () => {
    const grupoID = selectGrupo.value;
    contenedor.innerHTML = `<p class="text-muted">Cargando asistencia...</p>`;
    if (!grupoID) {
      contenedor.innerHTML = `<p class="text-muted">Seleccione un grupo válido.</p>`;
      return;
    }

    try {
      const url = `${window.Asistencia.matrizUrl}?grupo_id=${encodeURIComponent(grupoID)}`;
      const res = await fetch(url, { credentials: 'same-origin' });
      if (!res.ok) throw new Error('No se pudo cargar la asistencia');
      const data = await res.json();

      renderMatriz(data);
    } catch (e) {
      contenedor.innerHTML = `<div class="alert alert-danger">Error cargando la asistencia.</div>`;
      console.error(e);
    }
  });

  // ---- Render de la matriz (sesiones como columnas) ----
  function renderMatriz(data) {
    const meta = data.meta || {};
    const sesiones = Array.isArray(data.sesiones) ? data.sesiones : [];
    const participantes = Array.isArray(data.participantes) ? data.participantes : [];

    // Encabezado del grupo
    const encabezado = `
      <div class="bg-body-light rounded p-3 mb-2 fs-sm">
        <strong>${escapeHtml(meta.nombre_curso || '')}</strong>
        — ${escapeHtml(meta.jornada || '')}, ${escapeHtml(meta.dia || '')}
        — Salón: ${escapeHtml(meta.salon || '')} <br>
        Área: ${escapeHtml(meta.area || '')}
      </div>`;

    if (!participantes.length) {
      contenedor.innerHTML = `
        ${encabezado}
        <div class="alert alert-warning d-flex align-items-center" role="alert">
          <i class="fa fa-info-circle me-2"></i>
          <div>No hay registros de asistencia disponibles para este grupo.</div>
        </div>`;
      return;
    }

    // Tabla
    let html = `
      <div class="table-responsive">
        <table class="table table-bordered fs-xs text-center">
          <thead class="bg-body-dark">
            <tr>
              <th>Nombre</th>
              <th>Documento</th>
              <th>Convenio</th>`;

    sesiones.forEach(s => {
      html += `<th class="fs-sm">Sesión ${s.num}<br><span class="text-muted fs-sm">${escapeHtml(s.fecha || '')}</span></th>`;
    });

    html += `</tr></thead><tbody>`;

    participantes.forEach(p => {
      html += `
        <tr>
          <td class="text-start">${escapeHtml(p.nombre || '')}</td>
          <td>${escapeHtml(p.doc || '')}</td>
          <td>${escapeHtml(p.convenio || '')}</td>`;

      sesiones.forEach(s => {
        const marca = p.sesiones?.[s.num] === true;
        html += `<td>${marca ? '✔️' : '❌'}</td>`;
      });

      html += `</tr>`;
    });

    html += `</tbody></table></div>`;

    contenedor.innerHTML = encabezado + html;
  }

  // ---- Utils ----
  function resetSelect(sel, placeholder = 'Seleccionar', disabled = false) {
    sel.innerHTML = '';
    sel.appendChild(new Option(placeholder, ''));
    sel.disabled = !!disabled;
  }
  function setLoading(sel, text = 'Cargando...') {
    resetSelect(sel, text, true);
  }
  function setError(sel, text = 'Error') {
    resetSelect(sel, text, true);
  }
  function escapeHtml(str) {
    return String(str).replace(/[&<>"'`=\/]/g, s => ({
      '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;'
    }[s]));
  }
</script>
@endsection
