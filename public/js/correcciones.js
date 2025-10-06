// --- Config inyectada desde Blade ---
const URLS = {
  buscarParticipante: window.CorreccionesCFG.buscarParticipante,
  periodosTpl:       window.CorreccionesCFG.periodosTpl,          // /correcciones/participantes/{id}/periodos
  gruposJson:        window.CorreccionesCFG.gruposJson,           // /correcciones/asistencia/participante/grupos-json
  sesionesTpl:       window.CorreccionesCFG.sesionesTpl,          // /correcciones/asistencia/sesiones/{PID}/{GID}
  guardarCorrecciones: window.CorreccionesCFG.guardarCorrecciones // /correcciones/asistencia/guardar
};
const CSRF = window.CorreccionesCFG.csrf;

// ---------------------- Helpers HTTP/UI ----------------------
async function getJson(url, opts = {}) {
  const res = await fetch(url, {
    method: 'GET',
    credentials: 'same-origin',
    headers: { 'Accept':'application/json', 'X-Requested-With':'XMLHttpRequest', ...(opts.headers||{}) }
  });
  if (res.status === 422) {
    const j = await res.json().catch(()=>({}));
    throw new Error('HTTP 422 - ' + JSON.stringify(j.errors||j));
  }
  if (!res.ok) {
    let extra = '';
    try { extra = ' - ' + JSON.stringify(await res.json()); } catch(_) {}
    throw new Error('HTTP '+res.status+extra);
  }
  return res.json();
}
function esc(str){ return String(str).replace(/[&<>"'`=\/]/g, s => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;'}[s])); }
function buildInitials(name){ return String(name).trim().split(/\s+/).slice(0,2).map(s=>s[0]||'').join('').toUpperCase(); }
function showMessage(text, tone='secondary'){ msg.className = `alert alert-${tone} alert-slim mt-3`; msg.textContent = text; msg.classList.remove('d-none'); }
function clearMessage(){ msg.className='d-none'; msg.textContent=''; }
function skeleton(){ return `<li class="list-group-item"><div class="d-flex align-items-center gap-3 py-2"><div class="avatar placeholder col-1"></div><div class="flex-fill w-100"><div class="placeholder-glow"><span class="placeholder col-5"></span><span class="placeholder col-3 ms-2"></span></div></div></div></li>`; }
function chipsSkeleton(n=3){ return Array.from({length:n}).map(()=>`<span class="placeholder col-2 rounded-pill" style="height:32px;"></span>`).join(' '); }
function gruposSkeleton(n=2){ return Array.from({length:n}).map(()=>`<li class="list-group-item"><div class="placeholder-glow"><span class="placeholder col-5"></span><span class="placeholder col-3 ms-2"></span></div></li>`).join(''); }

// ---------------------- Refs búsqueda ----------------------
const frm  = document.getElementById('frm-buscar');
const btn  = document.getElementById('btnBuscar');
const spin = document.getElementById('btnSpinner');
const box  = document.getElementById('resultado');
const ul   = document.getElementById('lista');
const msg  = document.getElementById('msg');
const tipoEl = document.getElementById('tipo_doc');
const docEl  = document.getElementById('documento');

function setLoading(on){ btn.disabled = on; spin.classList.toggle('d-none', !on); }
function showResults(){ box.classList.remove('d-none'); }
function hideResults(){ box.classList.add('d-none'); ul.innerHTML = ''; }
function resetResults(){ hideResults(); }
function clearValidation(){ tipoEl.classList.remove('is-invalid'); docEl.classList.remove('is-invalid'); }

tipoEl.addEventListener('change', () => docEl.focus());
docEl.addEventListener('keydown', (e)=>{ if(e.key==='Enter'){ frm.requestSubmit(); } });

frm.addEventListener('submit', async (e) => {
  e.preventDefault();
  clearValidation(); clearMessage(); resetResults(); resetPeriodos(); resetGrupos(); resetSesiones();

  const tipo = tipoEl.value;
  const doc  = docEl.value.trim();
  let invalid = false;
  if (!tipo) { tipoEl.classList.add('is-invalid'); invalid = true; }
  if (!doc)  { docEl.classList.add('is-invalid');  invalid = true; }
  if (invalid) return;

  setLoading(true);
  showResults();
  ul.innerHTML = skeleton();

  try{
    const res = await fetch(URLS.buscarParticipante, {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'Content-Type':'application/json',
        'Accept':'application/json',
        'X-Requested-With':'XMLHttpRequest',
        'X-CSRF-TOKEN': CSRF
      },
      // <- mantiene snake_case de tu backend
      body: JSON.stringify({ tipo_doc: tipo, documento: doc })
    });

    if (!res.ok) { notFound(); return; }

    const ct = res.headers.get('content-type') || '';
    const payload = ct.includes('application/json') ? await res.json() : null;

    const raw = Array.isArray(payload) ? payload
               : (payload && Array.isArray(payload.data)) ? payload.data
               : [];

    const list = raw.filter(p =>
      p && Number(p.id) > 0 &&
      String(p.nombre || '').trim().length &&
      String(p.documento || '').trim().length
    );

    if (!list.length) { notFound(); return; }

    ul.innerHTML = '';
    list.forEach(p => ul.appendChild(renderItem(p)));

  } catch (err) {
    console.error(err);
    errorMsg();
  } finally {
    setLoading(false);
  }
});

function notFound(){ hideResults(); showMessage('Participante no encontrado.', 'warning'); }
function errorMsg(){ hideResults(); showMessage('Ocurrió un error al buscar. Inténtalo nuevamente.', 'danger'); }

// Render item participante
function renderItem(p){
  const initials = buildInitials(p.nombre ?? '');
  const li = document.createElement('li');
  li.className = 'list-group-item list-item';
  li.innerHTML = `
    <div class="avatar">${initials}</div>
    <div>
      <div class="title">${esc(p.nombre ?? '')}</div>
      <div class="meta">${esc(p.tipo_doc ?? '')} · ${esc(p.documento ?? '')}</div>
    </div>
    <div class="actions">
      <button class="btn btn-success btn-sm">Seleccionar</button>
    </div>
  `;
  li.querySelector('button').addEventListener('click', () => seleccionarParticipante(p));
  return li;
}

// ---------------------- Refs Periodos/Grupos ----------------------
const panelPeriodos = document.getElementById('panel-periodos');
const chipsPeriodos = document.getElementById('chips-periodos');
const alertPeriodos = document.getElementById('alert-periodos');
const resumenPart   = document.getElementById('resumen-participante');

const panelGrupos = document.getElementById('panel-grupos');
const listaGrupos = document.getElementById('lista-grupos');
const alertGrupos = document.getElementById('alert-grupos');
const resumenPer  = document.getElementById('resumen-periodo');

let participanteActual = null;
let periodoActualId    = null;
let periodosCache      = []; // [{id,nombre}]

function resetPeriodos(){ chipsPeriodos.innerHTML=''; alertPeriodos.classList.add('d-none'); panelPeriodos.classList.add('d-none'); periodosCache=[]; periodoActualId=null; }
function resetGrupos(){ listaGrupos.innerHTML=''; alertGrupos.classList.add('d-none'); panelGrupos.classList.add('d-none'); }

// ---- Paso 2: seleccionar participante -> cargar periodos
async function seleccionarParticipante(p){
  participanteActual = p;
  periodoActualId = null;
  periodosCache = [];

  clearMessage(); hideResults(); resetPeriodos(); resetGrupos(); resetSesiones();

  resumenPart.textContent = `${p.nombre} · ${p.tipo_doc} ${p.documento}`;

  const url = URLS.periodosTpl.replace('__ID__', encodeURIComponent(p.id));
  chipsPeriodos.innerHTML = chipsSkeleton(3);
  panelPeriodos.classList.remove('d-none');

  try {
    const data = await getJson(url);
    const periodos = Array.isArray(data) ? data : (data.periodos ?? []);

    periodosCache = periodos
      .map(per => ({ id: Number(per.id ?? per.calendario_id ?? 0), nombre: String(per.nombre ?? per.periodo ?? '') }))
      .filter(p => p.id > 0 && p.nombre);

    paintChips(periodosCache);
  } catch (e) {
    console.error(e);
    chipsPeriodos.innerHTML = '';
    alertPeriodos.classList.remove('d-none');
    alertPeriodos.textContent = 'No fue posible cargar los periodos.';
  }
}

function paintChips(periodos){
  chipsPeriodos.innerHTML = '';
  alertPeriodos.classList.add('d-none');

  if (!periodos.length){
    alertPeriodos.classList.remove('d-none');
    return;
  }

  periodos.forEach((per, idx) => {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'btn btn-outline-primary btn-sm rounded-pill';
    btn.textContent = per.nombre;
    btn.dataset.id = per.id;
    btn.addEventListener('click', () => on_periodo_click(per.id));
    chipsPeriodos.appendChild(btn);

    if (periodos.length === 1 && idx === 0) on_periodo_click(per.id); // autoselect
  });
}

// ---- Paso 3: click en periodo -> pedir GRUPOS
async function on_periodo_click(periodo_id){
  periodoActualId = periodo_id;

  // activar chip
  [...chipsPeriodos.querySelectorAll('button')].forEach(b=>{
    const active = Number(b.dataset.id) === Number(periodo_id);
    b.classList.toggle('btn-primary', active);
    b.classList.toggle('text-white', active);
    b.classList.toggle('btn-outline-primary', !active);
  });

  const per = periodosCache.find(x => Number(x.id) === Number(periodo_id));
  resumenPer.textContent = per ? per.nombre : '';

  resetGrupos(); resetSesiones();
  listaGrupos.innerHTML = gruposSkeleton(2);
  panelGrupos.classList.remove('d-none');

  try{
    const url = `${URLS.gruposJson}?participante_id=${encodeURIComponent(participanteActual.id)}&periodo_id=${encodeURIComponent(periodo_id)}`;
    const data = await getJson(url);
    const grupos = Array.isArray(data) ? data : (data.grupos ?? []);
    paintGrupos(grupos);
  }catch(e){
    console.error(e);
    listaGrupos.innerHTML = '';
    alertGrupos.classList.remove('d-none');
    alertGrupos.textContent = 'No fue posible cargar los grupos.';
  }
}

function paintGrupos(grupos){
  listaGrupos.innerHTML = '';
  alertGrupos.classList.add('d-none');

  if (!Array.isArray(grupos) || !grupos.length){
    alertGrupos.classList.remove('d-none');
    alertGrupos.textContent = 'No se encontraron grupos en este periodo.';
    return;
  }

  grupos.forEach(g => {
    const li = document.createElement('li');
    li.className = 'list-group-item d-flex align-items-center gap-3';
    li.innerHTML = `
      <div class="flex-fill">
        <div class="fw-semibold">${esc(g.curso ?? g.nombreCurso ?? '')}</div>
        <div class="text-muted small">${esc(g.dia ?? '')} · ${esc(g.jornada ?? '')} · ${esc(g.salon ?? '')}</div>
      </div>
      <span class="badge text-bg-light me-2">${Number(g.sesiones_registradas ?? 0)} sesiones registradas</span>
      <button class="btn btn-outline-primary btn-sm">Elegir</button>
    `;
    li.querySelector('button').addEventListener('click', () => on_elegir_grupo(g));
    listaGrupos.appendChild(li);
  });
}

// ---------------------- Refs Sesiones ----------------------
const panelSesiones   = document.getElementById('panel-sesiones');
const contSesiones    = document.getElementById('contenedor-sesiones');
const alertSesiones   = document.getElementById('alert-sesiones');
const resumenGrupo    = document.getElementById('resumen-grupo');

let grupoActual = null;
let cambiosPendientes = {}; // { [sesion_id]: { sesion_id, asistio_inicial, asistio_nuevo } }

function resetSesiones(){
  if (!panelSesiones || !contSesiones || !alertSesiones) return;
  panelSesiones.classList.add('d-none');
  contSesiones.innerHTML = '';
  alertSesiones.classList.add('d-none');
  cambiosPendientes = {};
}

// ---- Paso 4: elegir grupo -> pedir SESIONES y renderizar formulario
async function on_elegir_grupo(g){
  if (!participanteActual || !g || !g.id) return;

  grupoActual = g;
  cambiosPendientes = {};

  try{
    const url = URLS.sesionesTpl
      .replace('__PID__', encodeURIComponent(participanteActual.id))
      .replace('__GID__', encodeURIComponent(g.id));

    const data = await getJson(url);
    // data = { ultimo_registro: <int>, sesiones: [{id, fecha, hora, nombre, asistio (0|1)}] }
    render_formulario_sesiones(data);
  }catch(e){
    console.error(e);
    showMessage('No fue posible cargar las sesiones del grupo.', 'danger');
  }
}

function render_formulario_sesiones(data){
  // --- Limpieza y estado ---
  panelSesiones?.classList.remove('d-none');
  alertSesiones?.classList.add('d-none');
  if (contSesiones) contSesiones.innerHTML = '';
  cambiosPendientes = {};

  const sesiones = Array.isArray(data?.sesiones) ? data.sesiones : [];
  const ultimo   = Number(data?.ultimo_registro ?? 0);

  // Encabezado (curso + último registro)
  const cursoTxt = String(grupoActual?.curso ?? grupoActual?.nombreCurso ?? '').trim();
  if (resumenGrupo) {
    resumenGrupo.textContent = [cursoTxt, `Último registro: ${ultimo || '—'}`]
      .filter(Boolean)
      .join(' · ');
  }

  if (!sesiones.length){
    alertSesiones?.classList.remove('d-none');
    return;
  }

  // --- Tabla: Sesión | Estado / Fecha | Asistió ---
  const table = document.createElement('table');
  table.className = 'table table-sm align-middle';
  table.innerHTML = `
    <thead>
      <tr>
        <th style="width:140px;">SESIÓN</th>
        <th>ESTADO / FECHA DE REGISTRO</th>
        <th style="width:120px; text-align:left;">ASISTIÓ</th>
      </tr>
    </thead>
    <tbody></tbody>
  `;
  const tbody = table.querySelector('tbody');

  // Normalizamos datos de cada sesión y pintamos filas
  sesiones
    .map((s, idx) => {
      // id estable de la SESIÓN (no del registro de asistencia). Probamos varias llaves.
      const session_id =
        Number(
          s.sesion_id ??
          s.session_id ??
          s.sid ??
          s.session ??
          s.sesion ??
          s.id ??               // a veces el backend manda "id" como id de sesión
          0
        );

      const numero = Number(s.numero ?? s.nro ?? s.orden ?? s.num ?? (idx + 1));
      const asistio = Number(s.asistio ?? s.presente ?? s.asistencia ?? 0);

      // Fecha de registro del participante (si existe)
      const fecha_registro = String(
        s.fecha_registro_participante ??
        s.fecha_registro ??
        s.fecha_asistencia ??
        s.fecha_participante ??
        s.fecha ??
        s.fecha_iso ??
        ''
      ).trim();

      return { session_id, numero, asistio, fecha_registro };
    })
    .sort((a,b) => a.numero - b.numero)
    .forEach((s) => {
      const tr = document.createElement('tr');

      let estadoTexto = '';
      let estadoClase = '';

      if (s.asistio && s.fecha_registro) {
        estadoTexto = esc(s.fecha_registro);
        estadoClase = 'text-success fw-semibold';
      } else if (!s.asistio && s.fecha_registro) {
        estadoTexto = 'No asistió';
        estadoClase = 'text-danger fw-semibold';
      } else {
        // no hay registro para el participante en esa sesión
        estadoTexto = 'Sin registro';
        estadoClase = 'text-muted fst-italic';
      }

      // switch habilitado sólo si tenemos un id de sesión válido
      const canToggle = Number.isFinite(s.session_id) && s.session_id > 0;

      tr.innerHTML = `
        <td>Sesión ${s.numero || ''}</td>
        <td class="${estadoClase}">${estadoTexto}</td>
        <td>
          <div class="form-check form-switch mb-0">
            <input class="form-check-input sesion-switch" type="checkbox"
              data-sesion_id="${canToggle ? s.session_id : ''}"
              ${s.asistio ? 'checked' : ''} ${!canToggle ? 'disabled' : ''} ${!canToggle ? 'title="No se encontró el id de la sesión"' : ''}>
          </div>
        </td>
      `;

      // Persistimos estado inicial/nuevo para detectar cambios (sólo si tenemos id válido)
      if (canToggle) {
        cambiosPendientes[s.session_id] = {
          sesion_id: s.session_id,
          asistio_inicial: s.asistio,
          asistio_nuevo: s.asistio
        };
      }

      tbody.appendChild(tr);
    });

  // --- Observación y acciones ---
  const obs = document.createElement('div');
  obs.className = 'mt-2';
  obs.innerHTML = `
    <label class="form-label mb-1">Observación o motivo (opcional)</label>
    <textarea id="obs_correccion" class="form-control" rows="2"
      placeholder="Ej. Corrección por error de digitación"></textarea>
  `;

  const footer = document.createElement('div');
  footer.className = 'd-flex align-items-center gap-2 mt-3';
  footer.innerHTML = `
    <button id="btn_guardar" type="button" class="btn btn-primary" disabled>Guardar cambios</button>
    <button id="btn_cancelar" type="button" class="btn btn-outline-secondary">Cancelar</button>
    <span id="hint_guardado" class="text-muted small ms-2"></span>
  `;

  contSesiones.appendChild(table);
  contSesiones.appendChild(obs);
  contSesiones.appendChild(footer);

  // --- Listeners de switches ---
  contSesiones.querySelectorAll('.sesion-switch').forEach(chk => {
    if (chk.disabled) return; // ignoramos si no hay id de sesión

    chk.addEventListener('change', () => {
      const sid = Number(chk.dataset.sesion_id);
      if (!Number.isFinite(sid) || !(sid in cambiosPendientes)) return;

      cambiosPendientes[sid].asistio_nuevo = chk.checked ? 1 : 0;
      evaluar_habilitar_guardar();
    });
  });

  // --- Botones finales ---
  document.getElementById('btn_cancelar').addEventListener('click', () => {
    panelSesiones?.classList.add('d-none');
    if (contSesiones) contSesiones.innerHTML = '';
    cambiosPendientes = {};
  });
  document.getElementById('btn_guardar').addEventListener('click', on_guardar_correcciones);

  // Estado inicial del botón guardar
  evaluar_habilitar_guardar();
}


function hay_cambios(){
  return Object.values(cambiosPendientes).some(x => x.asistio_inicial !== x.asistio_nuevo);
}
function evaluar_habilitar_guardar(){
  const btn = document.getElementById('btn_guardar');
  btn.disabled = !hay_cambios();
}

async function on_guardar_correcciones(){
  if (!hay_cambios()) return;
  const btn = document.getElementById('btn_guardar');
  const hint = document.getElementById('hint_guardado');
  btn.disabled = true;
  hint.textContent = 'Guardando…';

  // Solo sesiones cambiadas
  const cambios = Object.values(cambiosPendientes)
    .filter(x => x.asistio_inicial !== x.asistio_nuevo)
    .map(x => ({ sesion_id: x.sesion_id, asistio: x.asistio_nuevo }));

  const payload = {
    participante_id: Number(participanteActual.id),
    grupo_id: Number(grupoActual.id),
    cambios: cambios,
    observacion: (document.getElementById('obs_correccion')?.value || '').trim()
  };

  try{
    const res = await fetch(URLS.guardarCorrecciones, {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': CSRF
      },
      body: JSON.stringify(payload)
    });

    if (res.status === 422) {
      const j = await res.json();
      throw new Error('Validación: ' + JSON.stringify(j.errors || j));
    }
    if (!res.ok) {
      const j = await res.json().catch(()=>({}));
      throw new Error('Error HTTP ' + res.status + ' ' + JSON.stringify(j));
    }

    // éxito
    hint.textContent = 'Cambios guardados.';
    showMessage('Asistencias actualizadas correctamente.', 'success');

    // refrescar estado final
    await on_elegir_grupo(grupoActual);
  }catch(e){
    console.error(e);
    hint.textContent = '';
    showMessage('No fue posible guardar las correcciones. ' + e.message, 'danger');
    document.getElementById('btn_guardar').disabled = false;
  }
}
