<div class="bg-body-light mb-5">
  <div class="content content-full">
    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
      <div class="flex-grow-1">
        <h1 class="h3 fw-normal mb-1">
          {{ $participante->getNombreCompleto() }}
        </h1>
        <h2 class="fs-base lh-base fw-normal text-dark mb-0">
          {{ $participante->getDocumentoCompleto() }} <br>
          <span class="fs-sm text-dark">{{ \Carbon\Carbon::now('America/Bogota')->locale('es_CO')->isoFormat('D [de] MMMM [de] YYYY') }}</span>
        </h2>
      </div>

    </div>
  </div>
</div>

<div class="block block-rounded row g-0">
    <table class="table table-bordered table-vcenter">
        <thead>
        <tr class="text-center">
                <th colspan="10">Formularios pendientes de pago</th>
            </tr>            
            <tr class="text-center">
                <th>Formulario</th>
                <th>Periodo</th>
                <th>Curso</th>
                <th>Fec. Max. Legalización</th>
                <th>Estado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($participante->listarFormulariosPendientesDePago() as $formulario)
            <tr class="fs-sm">
                <td>{{ $formulario->getNumero() }}</td>
                <td>{{ $formulario->getGrupoCalendarioNombre() }}</td>
                <td>
                    <a href="#" class="fs-sm">{{ $formulario->getGrupoNombreCurso() }}</a>
                    <br>
                    <small>
                        G{{ $formulario->getGrupoId()  }}: 
                        {{ $formulario->getGrupoDia()  }} / {{ $formulario->getGrupoJornada() }}<br>{{ $formulario->getGrupoModalidad() }}
                    </small>                    
                </td>
                <td>{{ $formulario->getFechaMaxLegalizacion() }}</td>
                <td>
                    {{ $formulario->getEstado() }}
                    @if ($formulario->tieneConvenio())
                        Convenio: {{ $formulario->getConvenioNombre() }}
                    @endif                    
                </td>
                <td>
                    <a href="{{ route('public.inscribir-participante-a-grupo', [$formulario->getParticipanteId(), $formulario->getGrupoId(), $formulario->getId()]) }}" 
                            class="btn fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info"
                            data-bs-toggle="tooltip" 
                            title="Pagar"
                            >
                            Pagar
                    </a>                            
                </td>
            </tr>
            @endforeach 
        </tbody>
    </table> 

    <div class="col-12">

        <div class="mb-6 text-center">

                <a href="{{ route('public.seleccionar-curso', $formulario->getParticipanteId()) }}" 
                    class="btn fs-sm fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success"
                    data-bs-toggle="tooltip" 
                    title="Realizar otra inscripción">
                    Realizar nueva inscripción
                </a>

                <!-- <a href="{{ route('formulario-inscripcion.descargar-recibo-matricula', $formulario->getParticipanteId()) }}" 
                    class="btn fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-warning-light text-warning"
                    data-bs-toggle="tooltip" 
                    title="Finalizar">
                    Finalizar
                </a>                 -->

        </div>

    </div>
                    
</div>