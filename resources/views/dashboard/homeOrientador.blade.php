@extends("plantillas.principal")

@section("title", "Dashboard")
@section("description", "Információn de interés acerca del actual periodo académico")

@section("seccion", "Dashboard")
@section("subseccion", "Datos general")

@section("content")

<div class="row items-push">

<!-- Tarjetas -->
<div class="row mt-3">
  <!-- Tarjeta 1 -->
  <div class="col-md-6 col-xl-4">
    <a class="block block-rounded block-link-shadow bg-primary" href="#">
      <div class="block-content block-content-full d-flex align-items-center justify-content-between">
        <div>
          <i class="fa fa-calendar-alt fa-2x text-white-50"></i>
        </div>
        <dl class="ms-3 text-end mb-0">
          <dt class="text-white h3 fw-medium mb-0">{{ $datos["total_cursos_actuales"] }}</dt>
          <dd class="text-white fs-sm fw-medium text-muted mb-0">Mis cursos actuales</dd>
        </dl>
      </div>
    </a>
  </div>

  <!-- Tarjeta 2 -->
  <div class="col-md-6 col-xl-4">
    <a class="block block-rounded block-link-shadow bg-success" href="#">
      <div class="block-content block-content-full d-flex align-items-center justify-content-between">
        <div>
          <i class="fa fa-users fa-2x text-white-50"></i>
        </div>
        <dl class="ms-3 text-end mb-0">
          <dt class="text-white h3 fw-medium mb-0">{{ $datos["total_participantes"] }}</dt>
          <dd class="text-white fs-sm fw-medium text-muted mb-0">Participantes totales</dd>
        </dl>
      </div>
    </a>
  </div>

  <!-- Tarjeta 3 -->
  <div class="col-md-6 col-xl-4">
    <a class="block block-rounded block-link-shadow bg-warning" href="{{ route('asistencia.formulario') }}">
      <div class="block-content block-content-full d-flex align-items-center justify-content-between">
        <div>
          <i class="fa fa-calendar-check fa-2x text-white-50"></i>
        </div>
        <dl class="ms-3 text-end mb-0">
          <dt class="text-white h3 fw-medium mb-0">Registrar nueva asistencia</dt>
          <dd class="text-white fs-sm fw-medium text-muted mb-0"></dd>
        </dl>
      </div>
    </a>
  </div>
</div>

<!-- Calendario visual renovado -->
<div class="row mt-4">
  <div class="col-12">
    <div class="block block-rounded">
      <div class="block-header">
        <h3 class="block-title">Calendario de clases semanales</h3>
      </div>
      <div class="block-content">
        <div class="table-responsive">
          <table class="table table-bordered table-vcenter text-center" style="background-color: #fb4747;">
            <thead>
              <tr style="background-color: #fb4747;">
                <th>Lunes</th>
                <th>Martes</th>
                <th>Miércoles</th>
                <th>Jueves</th>
                <th>Viernes</th>
                <th>Sábado</th>
              </tr>
            </thead>

            <tbody>
              <tr class="fs-xs">
                @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'] as $dia)
                  <td>
                    @php
                      $jornadasOrden = ['Mañana', 'Tarde', 'Noche'];
                      $cursoIndex = 0;
                    @endphp

                    @foreach ($jornadasOrden as $jornadaDeseada)
                      @foreach (collect($datos['horario'][$dia])->where('jornada', $jornadaDeseada) as $curso)
                        <div class="{{ $cursoIndex > 0 ? 'mt-4' : '' }}">
                          <strong class="text-default">{{ $curso['nombre_curso'] }}</strong><br>
                          <div>{{ $curso['jornada'] }} - Salón {{ $curso['nombre_salon'] }}</div>
                          {{-- <div>Grupo: {{ $curso['codigo_grupo'] }}</div> --}} 
                          <div>{{ $curso['total_participantes'] }} estudiantes</div>
                        </div>
                        @php $cursoIndex++; @endphp
                      @endforeach
                    @endforeach
                  </td>
                @endforeach
              </tr>
            </tbody>



          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Clases programadas para hoy -->
<!-- <div class="row mt-4">
  <div class="col-12">
    <div class="block block-rounded">
      <div class="block-header">
        <h3 class="block-title">Clases programadas para hoy</h3>
      </div>
      <div class="block-content">
        <ul class="fs-sm">
          <li>Curso 1 – Salón 101 – Mañana</li>
          <li>Curso 2 – Salón 102 – Tarde</li>
          <li>Curso 3 – Salón 103 – Noche</li>
        </ul>
      </div>
    </div>
  </div>
</div> -->


@endsection