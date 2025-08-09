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
  window.Asistencia = {
    periodosUrl:  "{{ route('asistencia.periodos-json') }}",
    gruposUrl:    "{{ route('asistencia.grupos-json') }}",
    sesionesUrl:  "{{ route('asistencia.sesiones-json') }}",
    detalleUrl:   "{{ route('asistencia.asistencia-json') }}",
  };
</script>

<script>
  const periodoSelect = document.getElementById('periodo');
  const grupoSelect   = document.getElementById('grupo_id');
  const sesionSelect  = document.getElementById('sesion');
  const infoExtra     = document.getElementById('info-extra');
  const tbody         = document.querySelector('#tabla-asistencia tbody');

  // Estado inicial
  resetSelect(grupoSelect, 'Seleccione primero un periodo', true);
  resetSelect(sesionSelect, 'Seleccione grupo', true);
  pintarMensajeTabla('Seleccione un grupo y sesión.');
  infoExtra.style.display = 'none';

  // 1) Cargar periodos al cargar la página
  document.addEventListener('DOMContentLoaded', cargarPeriodos);

  async function cargarPeriodos() {
    resetSelect(periodoSelect, 'Cargando periodos...', true);
    periodoSelect.style.cursor = 'wait'; // Mejora #1: feedback visual
    try {
      const res = await fetch(window.Asistencia.periodosUrl, { credentials: 'same-origin' });
      if (!res.ok) throw new Error('No se pudieron cargar los periodos');
      const periodos = await res.json();

      // Mejora #3: manejar lista vacía de periodos
      if (!periodos.length) {
        resetSelect(periodoSelect, 'Sin periodos disponibles', true);
        return;
      }

      resetSelect(periodoSelect, 'Seleccionar periodo');
      periodos.forEach(p => periodoSelect.appendChild(new Option(p.nombre, p.id)));
    } catch (e) {
      resetSelect(periodoSelect, 'Error cargando periodos', true);
      console.error(e);
    } finally {
      periodoSelect.style.cursor = ''; // volver cursor normal
    }
  }

  // 2) Al cambiar periodo → cargar grupos
  periodoSelect.addEventListener('change', async () => {
    const periodoId = periodoSelect.value;

    resetSelect(grupoSelect, 'Seleccionar grupo', true);
    resetSelect(sesionSelect, 'Seleccionar sesión', true);
    infoExtra.style.display = 'none';
    pintarMensajeTabla('Seleccione un grupo y sesión.');

    if (!periodoId) return;

    setLoading(grupoSelect, 'Cargando grupos...');
    grupoSelect.style.cursor = 'wait'; // Mejora #1
    try {
      const url = `${window.Asistencia.gruposUrl}?periodo_id=${encodeURIComponent(periodoId)}`;
      const res = await fetch(url, { credentials: 'same-origin' });
      if (!res.ok) throw new Error('No se pudieron cargar los grupos');
      const grupos = await res.json();

      resetSelect(grupoSelect, 'Seleccionar grupo');
      grupos.forEach(g => {
        const text = `${g.nombre_curso} (${g.dia} - ${g.jornada})`;
        const opt  = new Option(text, g.id);
        opt.dataset.meta = JSON.stringify(g);
        grupoSelect.appendChild(opt);
      });
      grupoSelect.disabled = false;
    } catch (e) {
      resetSelect(grupoSelect, 'Error cargando grupos', true);
      console.error(e);
    } finally {
      grupoSelect.style.cursor = ''; // volver cursor normal
    }
  });

  // 3) Al cambiar grupo → cargar sesiones registradas
  grupoSelect.addEventListener('change', async () => {
    const grupoId = grupoSelect.value;

    resetSelect(sesionSelect, 'Seleccionar sesión', true);
    infoExtra.style.display = 'none';
    pintarMensajeTabla('Seleccione una sesión.');

    if (!grupoId) return;

    setLoading(sesionSelect, 'Cargando sesiones...');
    sesionSelect.style.cursor = 'wait'; // Mejora #1
    try {
      const url = `${window.Asistencia.sesionesUrl}?grupo_id=${encodeURIComponent(grupoId)}`;
      const res = await fetch(url, { credentials: 'same-origin' });
      if (!res.ok) throw new Error('No se pudieron cargar las sesiones');
      const sesiones = await res.json();

      resetSelect(sesionSelect, 'Seleccionar sesión');

      // Mejora #2: si no hay registros, mostramos 1–16
      if (!sesiones.length) {
        for (let i = 1; i <= 16; i++) {
          sesionSelect.appendChild(new Option(`Sesión ${i}`, i));
        }
        sesionSelect.disabled = false;
      } else {
        sesiones.forEach(s => sesionSelect.appendChild(new Option(`Sesión ${s.num}`, s.num)));
        sesionSelect.disabled = false;
      }
    } catch (e) {
      resetSelect(sesionSelect, 'Error cargando sesiones', true);
      console.error(e);
    } finally {
      sesionSelect.style.cursor = ''; // volver cursor normal
    }
  });

  // 4) Al cambiar sesión → cargar detalle de asistencia
sesionSelect.addEventListener('change', async () => {
  const grupoId = grupoSelect.value;
  const sesion  = sesionSelect.value;

  infoExtra.style.display = 'none';
  pintarMensajeTabla('Cargando asistencia...');

  if (!grupoId || !sesion) {
    pintarMensajeTabla('Seleccione un grupo y sesión.');
    return;
  }

  try {
    const url = `${window.Asistencia.detalleUrl}?grupo_id=${encodeURIComponent(grupoId)}&sesion=${encodeURIComponent(sesion)}`;
    const res = await fetch(url, { credentials: 'same-origin' });
    if (!res.ok) throw new Error('No se pudo cargar la asistencia');
    const data = await res.json();

    // ---- Info extra (compatibilidad: meta o grupo) ----
    const meta = data.meta ?? data.grupo ?? {};
    infoExtra.style.display = 'block';
    document.getElementById('info-curso').textContent   = meta.nombre_curso || '';
    document.getElementById('info-jornada').textContent = meta.jornada || '';
    document.getElementById('info-salon').textContent   = meta.salon || '';
    document.getElementById('info-dia').textContent     = meta.dia || '';
    document.getElementById('info-area').textContent    = meta.area || '';
    const fecha = meta.fecha || (Array.isArray(data.participantes) && data.participantes[0]?.fecha) || '';
    document.getElementById('info-fecha-registro').textContent = fecha;

    // ---- Tabla ----
    const items = data.participantes || [];
    if (!items.length) {
      pintarMensajeTabla('Sin registros para esta sesión.');
      return;
    }

    tbody.innerHTML = '';
    items.forEach(p => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td class="text-start">${escapeHtml(p.nombre || '')}</td>
        <td>${escapeHtml(p.doc || '')}</td>
        <td>${escapeHtml(p.convenio || '')}</td>
        <td>${p.presente ? '✔️' : '❌'}</td>
      `;
      tbody.appendChild(tr);
    });
  } catch (e) {
    pintarMensajeTabla('Error cargando asistencia.');
    console.error(e);
  }
});

  // Utils UI
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
  function pintarMensajeTabla(msg) {
    tbody.innerHTML = `<tr><td colspan="4" class="text-muted">${msg}</td></tr>`;
  }
  function escapeHtml(str) {
    return String(str).replace(/[&<>"'`=\/]/g, s => ({
      '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;'
    }[s]));
  }
</script>


@endsection
