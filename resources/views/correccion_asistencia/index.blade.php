@extends("plantillas.principal")

@section("title", "Corrección de asistencias")
@section("description", "Corrige asistencias ya tomadas por errores o cambios de curso, dejando constancia de cada modificación.")

@section('content')
<div class="container-lg my-4">

  {{-- BUSCADOR --}}
  <div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-3 p-md-4">
      <div class="d-flex align-items-center justify-content-between mb-2">
        
        <small id="hint" class="text-secondary">Ingresa tipo y número de documento</small>
      </div>

      <form id="frm-buscar" class="row g-3 align-items-end">
        <div class="col-12 col-md-4">
          <label for="tipo_doc" class="form-label mb-1">Tipo de documento</label>
          <select id="tipo_doc" class="form-select">
            <option value="">Selecciona…</option>
            <option value="CC">Cédula de ciudadanía</option>
            <option value="TI">Tarjeta de identidad</option>
            <option value="CE">Cédula de extranjería</option>
          </select>
          <div class="invalid-feedback">Selecciona el tipo.</div>
        </div>

        <div class="col-12 col-md-6">
          <label for="documento" class="form-label mb-1">Número de documento</label>
          <input id="documento" type="text" class="form-control"
                 placeholder="Ej. 1020456789"
                 inputmode="numeric" autocomplete="off" />
          <div class="invalid-feedback">Ingresa el número.</div>
        </div>

        <div class="col-12 col-md-2 d-grid">
          <button id="btnBuscar" type="submit" class="btn btn-primary">
            <span id="btnSpinner" class="spinner-border spinner-border-sm me-2 d-none"></span>
            Buscar
          </button>
        </div>
      </form>

      <div id="msg" class="d-none mt-3"></div>
    </div>
  </div>

  {{-- RESULTADOS --}}
  <div id="resultado" class="mt-3 d-none">
    <ul id="lista" class="list-group rounded-4 shadow-sm overflow-hidden"></ul>
  </div>

  <!-- PASO 2: PERIODOS DEL PARTICIPANTE -->
<div id="panel-periodos" class="mt-3 d-none">
  <div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-3 p-md-4">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <h2 class="h6 mb-0 text-muted">Selecciona el periodo</h2>
        <small class="text-secondary" id="resumen-participante"></small>
      </div>

      <div id="chips-periodos" class="d-flex flex-wrap gap-2"></div>

      <div id="alert-periodos" class="alert alert-warning alert-slim mt-3 d-none">
        El participante no tiene periodos asociados.
      </div>
    </div>
  </div>
</div>

<!-- PASO 3: GRUPOS EN EL PERIODO (lo llenaremos después) -->
<div id="panel-grupos" class="mt-3 d-none">
  <div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-3 p-md-4">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <h2 class="h6 mb-0 text-muted">Grupos del periodo</h2>
        <small class="text-secondary" id="resumen-periodo"></small>
      </div>

      <ul id="lista-grupos" class="list-group rounded-4 overflow-hidden"></ul>

      <div id="alert-grupos" class="alert alert-warning alert-slim mt-3 d-none">
        No se encontraron grupos en este periodo.
      </div>
    </div>
  </div>
</div>



</div>

<style>
  /* Card & form */
  .card { background: #fff; }
  .form-label { font-weight: 600; color:#374151; }
  .is-invalid + .invalid-feedback { display:block; }

  /* Result list */
  .list-item {
    display:flex; gap:12px; align-items:center; padding:12px 16px;
  }
  .list-item:hover { background:#f8fafc; }
  .avatar {
    width:40px; height:40px; border-radius:50%;
    display:grid; place-items:center; font-weight:700;
    background:#e8eef9; color:#2b5fd9;
    text-transform:uppercase;
    flex:0 0 40px;
  }
  .title { font-weight:700; line-height:1.1; }
  .meta  { color:#6b7280; font-size:.925rem; }
  .actions { margin-left:auto; display:flex; gap:.5rem; }
  .btn-ghost {
    background:#f3f4f6; color:#111827; border:0; border-radius:.75rem; padding:.4rem .7rem;
  }

  /* Messages */
  .alert-slim {
    border-radius:.75rem; padding:.6rem .8rem; margin:0;
  }
</style>

@endsection

@push('page-scripts')
  <script>
    window.CorreccionesCFG = {
      buscarParticipante: "{{ route('correcciones.buscar-participante') }}",
      periodosTpl: "{{ route('correcciones.participante.periodos', ['participanteId' => '__ID__']) }}",
      sesionesTpl: "{{ route('correcciones.asistencia.sesiones', ['participanteId' => '__PID__', 'grupoId' => '__GID__']) }}",
      gruposJson: "{{ route('correcciones.asistencia.grupos-json') }}", // <-- nuevo
      csrf: "{{ csrf_token() }}"
    };
  </script>
  <script src="{{ asset('js/correcciones.js') }}"></script>
@endpush



