// --- Config inyectada desde Blade ---
const URLS = {
  buscarParticipante: window.CorreccionesCFG.buscarParticipante,
  periodosTpl:       window.CorreccionesCFG.periodosTpl,   // /correcciones/participantes/{id}/periodos
  gruposJson:        window.CorreccionesCFG.gruposJson,    // /correcciones/asistencia/participante/grupos-json?participante_id=..&periodo_id=..
  sesionesTpl:       window.CorreccionesCFG.sesionesTpl,   // /correcciones/asistencia/sesiones/{PID}/{GID}
};
const CSRF = window.CorreccionesCFG.csrf;

// --- refs búsqueda ---
const frm  = document.getElementById('frm-buscar');
const btn  = document.getElementById('btnBuscar');
const spin = document.getElementById('btnSpinner');
const box  = document.getElementById('resultado');
const ul   = document.getElementById('lista');
const msg  = document.getElementById('msg');
const tipoEl = document.getElementById('tipo_doc');
const docEl  = document.getElementById('documento');

// --- refs Periodos / Grupos ---
const panelPeriodos = document.getElementById('panel-periodos');
const chipsPeriodos = document.getElementById('chips-periodos');
const alertPeriodos = document.getElementById('alert-periodos');
const resumenPart   = document.getElementById('resumen-participante');

const panelGrupos = document.getElementById('panel-grupos');
const listaGrupos = document.getElementById('lista-grupos');
const alertGrupos = document.getElementById('alert-grupos');
const resumenPer  = document.getElementById('resumen-periodo');

// --- estado ---
let participanteActual = null;
let periodoActualId    = null;
let periodosCache      = []; // [{id,nombre}]

// UX
tipoEl.addEventListener('change', () => docEl.focus());
docEl.addEventListener('keydown', (e)=>{ if(e.key==='Enter'){ frm.requestSubmit(); } });

// Helpers HTTP
async function getJson(url, opts = {}) {
  const res = await fetch(url, {
    method: 'GET',
    credentials: 'same-origin',
    headers: {
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
      ...(opts.headers || {})
    }
  });

  // Manejo explícito de 422 para ver validaciones del backend
  if (res.status === 422) {
    const err = await res.json().catch(() => ({}));
    const details = (err && err.errors) ? JSON.stringify(err.errors) : 'Validación falló.';
    throw new Error(`HTTP 422 - ${details}`);
  }

  if (!res.ok) {
    let extra = '';
    try { const j = await res.json(); extra = ` - ${JSON.stringify(j)}`; } catch(_) {}
    throw new Error(`HTTP ${res.status}${extra}`);
  }

  return res.json();
}

// Submit buscador
frm.addEventListener('submit', async (e) => {
  e.preventDefault();
  clearValidation(); clearMessage(); resetResults(); resetPeriodos(); resetGrupos();

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
      // Mantengo snake_case en la búsqueda para consistencia
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

// ---- Paso 2: seleccionar participante -> cargar periodos
async function seleccionarParticipante(p){
  participanteActual = p;
  periodoActualId = null;
  periodosCache = [];

  clearMessage();
  hideResults();
  resetPeriodos();
  resetGrupos();

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

// ---- Paso 3: click en periodo -> pedir GRUPOS al endpoint grupos-json (snake_case)
async function on_periodo_click(periodo_id){
  periodoActualId = periodo_id;

  // activar chip
  [...chipsPeriodos.querySelectorAll('button')].forEach(b=>{
    const active = Number(b.dataset.id) === Number(periodo_id);
    b.classList.toggle('btn-primary', active);
    b.classList.toggle('text-white', active);
    b.classList.toggle('btn-outline-primary', !active);
  });

  // resume
  const per = periodosCache.find(x => Number(x.id) === Number(periodo_id));
  resumenPer.textContent = per ? per.nombre : '';

  resetGrupos();
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

// ---- Paso 4: elegir grupo -> pedir SESIONES (para el formulario)
async function on_elegir_grupo(g){
  if (!participanteActual || !g || !g.id) return;

  try{
    const url = URLS.sesionesTpl
      .replace('__PID__', encodeURIComponent(participanteActual.id))
      .replace('__GID__', encodeURIComponent(g.id));

    const data = await getJson(url);
    console.log('Sesiones recibidas:', data);
    // TODO: render del formulario con data.sesiones y data.ultimo_registro
  }catch(e){
    console.error(e);
    showMessage('No fue posible cargar las sesiones del grupo.', 'danger');
  }
}

// --- Helpers ---
function notFound(){ hideResults(); showMessage('Participante no encontrado.', 'warning'); }
function errorMsg(){ hideResults(); showMessage('Ocurrió un error al buscar. Inténtalo nuevamente.', 'danger'); }
function showResults(){ box.classList.remove('d-none'); }
function hideResults(){ box.classList.add('d-none'); ul.innerHTML = ''; }
function resetResults(){ hideResults(); }
function resetPeriodos(){ chipsPeriodos.innerHTML=''; alertPeriodos.classList.add('d-none'); panelPeriodos.classList.add('d-none'); periodosCache=[]; periodoActualId=null; }
function resetGrupos(){ listaGrupos.innerHTML=''; alertGrupos.classList.add('d-none'); panelGrupos.classList.add('d-none'); }

function buildInitials(name){ return String(name).trim().split(/\s+/).slice(0,2).map(s=>s[0]||'').join('').toUpperCase(); }
function skeleton(){ return `<li class="list-group-item"><div class="d-flex align-items-center gap-3 py-2"><div class="avatar placeholder col-1"></div><div class="flex-fill w-100"><div class="placeholder-glow"><span class="placeholder col-5"></span><span class="placeholder col-3 ms-2"></span></div></div></div></li>`; }
function chipsSkeleton(n=3){ return Array.from({length:n}).map(()=>`<span class="placeholder col-2 rounded-pill" style="height:32px;"></span>`).join(' '); }
function gruposSkeleton(n=2){ return Array.from({length:n}).map(()=>`<li class="list-group-item"><div class="placeholder-glow"><span class="placeholder col-5"></span><span class="placeholder col-3 ms-2"></span></div></li>`).join(''); }
function setLoading(on){ btn.disabled = on; spin.classList.toggle('d-none', !on); }
function esc(str){ return String(str).replace(/[&<>"'`=\/]/g, s => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;'}[s])); }
function clearValidation(){ tipoEl.classList.remove('is-invalid'); docEl.classList.remove('is-invalid'); }
function showMessage(text, tone='secondary'){ msg.className = `alert alert-${tone} alert-slim mt-3`; msg.textContent = text; msg.classList.remove('d-none'); }
function clearMessage(){ msg.className='d-none'; msg.textContent=''; }
