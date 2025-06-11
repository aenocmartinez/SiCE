@extends("plantillas.principal")

@php
    $titulo = "Estadísticas del periodo";
@endphp

@section("title", $titulo)

@section("description", "")

@section("seccion")
    <a class="link-fx" href="{{ route('calendario.index') }}">
        Periodo académico
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")
<div class="block block-rounded">

    <div class="block-content">

      <div class="block block-rounded">
            <div class="block-content text-center">
              <div class="py-4">

                <h1 class="fs-lg mb-0">
                  <span>Periodo {{ $data['nombre'] }}</span>
                </h1>
                <p class="fs-sm fw-medium text-muted">
                    {{ $data['fechaInicio'] }} / {{ $data['fechaFin'] }} <br>
                    {{ $data['estado'] }}
                </p>
              </div>
            </div>

            <div class="block-content bg-body-light text-center">
              <div class="row items-push text-uppercase">
                <!-- <div class="col-6 col-md-4">
                  <div class="fw-semibold text-dark mb-1"># form. matriculados</div>
                  <a class="link-fx fs-5 text-primary" href="javascript:void(0)">{{ $data["totalInscripcionesLegalizadas"] }}</a>
                </div>                 -->
                <div class="col-6 col-md-6">
                  <div class="fw-semibold text-dark mb-1"># form. por convenio</div>
                  <a class="link-fx fs-5 text-primary" href="javascript:void(0)">{{ $data["totalInscripcionesLegalizadasPorConvenio"] }}</a>
                </div>             
                <div class="col-6 col-md-6">
                  <div class="fw-semibold text-dark mb-1"># form. sin descuento</div>
                  <a class="link-fx fs-5 text-primary" href="javascript:void(0)">{{ $data["totalInscripcionesLegalizadasRegulares"] }}</a>
                </div>                                
              </div>
            </div>

            <div class="block-content bg-body-light text-center">
              <div class="row items-push text-uppercase">         
                <div class="col-6 col-md-4">
                  <div class="fw-semibold text-dark mb-1"
                      data-bs-toggle="tooltip" 
                    title="Suma de recaudo por convenio + recuado sin descuento"
                    >Total recaudo                   
                  </div>
                  <a class="link-fx fs-6 text-primary"
                   href="javascript:void(0)">{{ $data["total_recaudo"] }}</a>
                </div>
                <div class="col-6 col-md-4">
                  <div class="fw-semibold text-dark mb-1">Recaudo convenios</div>
                  <a class="link-fx fs-6 text-primary" href="javascript:void(0)">{{ $data['total_por_convenio'] }}</a>
                </div>
                <div class="col-6 col-md-4">
                  <div class="fw-semibold text-dark mb-1">Recaudo sin dcto</div>
                  <a class="link-fx fs-6 text-primary" href="javascript:void(0)">{{ $data['total_sin_convenio'] }}</a>
                </div>
                <!-- <div class="col-6 col-md-3">
                  <div class="fw-semibold text-dark mb-1">Aplazados</div>
                  <a class="link-fx fs-6 text-primary" href="javascript:void(0)">{{ $data['total_aplazados'] }}</a>
                </div>                                 -->
              </div>
            </div>

            <div class="block-content bg-body-light text-center">
              <div class="row items-push text-uppercase">         
                <div class="col-6 col-md-6">
                  <div class="fw-semibold text-dark mb-1">Inscripciones en oficina</div>
                  <a class="link-fx fs-6 text-primary" href="javascript:void(0)">{{ $data["totalFormularioInscritosEnOficina"] }}</a>
                </div>
                <div class="col-6 col-md-6">
                  <div class="fw-semibold text-dark mb-1">Inscripciones formulario público</div>
                  <a class="link-fx fs-6 text-primary" href="javascript:void(0)">{{ $data['totalFormularioInscritosEnLinea'] }}</a>
                </div>
              </div>
            </div>            

          </div>


          <div class="block-content block-content-full">

            <div class="row text-center">
              
                <div class="col-6">    
                  <a href="{{ route('calendario.descargar-participantes', $calendarioId) }}" type="button" class="btn btn-lg rounded-pill btn-alt-success px-4 me-1 mb-3">
                    <i class="fa fa-download me-1"></i> Descargar los participantes del periodo
                  </a>
                </div>

                <div class="col-6">    
                  <a href="{{ route('calendario.descargar-cuadro-110', $calendarioId) }}" type="button" class="btn btn-lg rounded-pill btn-alt-info px-4 me-1 mb-3">
                    <i class="fa fa-download me-1"></i> Número de cursos y participantes por jornada
                  </a>
                </div>

            </div>

          </div>
          

          <div class="block block-rounded">
           
            <div class="block-content bg-body-light text-center">

              <div class="row items-push">

              <!-- Total Matriculados -->
              <div class="col-sm-6 col-xxl-4">

                  <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                      <dl class="mb-0">
                        <dt class="fs-3 fw-bold">{{ $data['totalMatriculados'] }}</dt>
                        <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Formularios matriculados</dd>
                      </dl>
                      <div class="item item-rounded-lg bg-body-light">
                        <i class="far fa-paper-plane fs-3 text-primary"></i>
                      </div>
                    </div>
                    <!-- <div class="bg-body-light rounded-bottom">
                      <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ route('dashboard.buscar-formularios', 'Anulado') }}">
                        <span>Ver matriculados</span>
                        <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                      </a>
                    </div> -->
                  </div>

                </div>
              <!-- Fin Total Matriculado -->

                <!-- Tarjeta Total Anulados -->
                <div class="col-sm-6 col-xxl-4">

                  <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                      <dl class="mb-0">
                        <dt class="fs-3 fw-bold">{{ $data['totalAnulados'] }}</dt>
                        <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Formularios anulados</dd>
                      </dl>
                      <div class="item item-rounded-lg bg-body-light">
                        <i class="far fa-paper-plane fs-3 text-primary"></i>
                      </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                      <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ route('dashboard.buscar-formularios', 'Anulado') }}?periodo={{ $calendarioId }}">
                        <span>Ver formularios anulados</span>
                        <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                      </a>
                    </div>
                  </div>

                </div>
                <!-- Fin Tarjeta Total Anulados -->

                <!-- Tarjeta pendiente de pago -->
                <div class="col-sm-6 col-xxl-4">
                  
                  <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                      <dl class="mb-0">
                        <dt class="fs-3 fw-bold">{{ $data['totalPendintesDePago'] }}</dt>
                        <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Formularios pendientes de legalizar</dd>
                      </dl>
                      <div class="item item-rounded-lg bg-body-light">
                        <i class="fa fa-chart-bar fs-3 text-primary"></i>
                      </div>
                    </div>
                    <div class="bg-body-light rounded-bottom">
                      <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ route('dashboard.buscar-formularios', 'Pendiente de pago') }}?periodo={{ $calendarioId }}">
                        <span>Ver formularios pendientes de legalizar</span>
                        <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                      </a>
                    </div>
                  </div>
                  
                </div>    
                <!-- Fin Tarjeta pendiente de pago -->

            <!-- Tarjeta Devolución -->
            <div class="col-sm-6 col-xxl-4">
                <div class="block block-rounded d-flex flex-column h-100 mb-0">
                  <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                      <dt class="fs-3 fw-bold">{{ $data['totalDevolucion'] }}</dt>
                      <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Formularios devueltos</dd>
                    </dl>
                    <div class="item item-rounded-lg bg-body-light">
                    <i class="fas fa-history fs-3 text-primary"></i>
                    </div>
                  </div>
                  <div class="bg-body-light rounded-bottom">
                    <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ route('dashboard.buscar-formularios', 'Devuelto') }}?periodo={{ $calendarioId }}">
                      <span>Ver formularios devueltos</span>
                      <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                    </a>
                  </div>
                </div>
            </div>                           
            <!-- Fin tarjeta Devolución -->      
             
            <!-- Tarjeta Aplazados -->
            <div class="col-sm-6 col-xxl-4">
                <div class="block block-rounded d-flex flex-column h-100 mb-0">
                  <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                      <dt class="fs-3 fw-bold">{{ $data['totalAplazados'] }}</dt>
                      <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Formularios aplazados</dd>
                    </dl>
                    <div class="item item-rounded-lg bg-body-light">
                    <i class="fas fa-history fs-3 text-primary"></i>
                    </div>
                  </div>
                  <div class="bg-body-light rounded-bottom">
                    <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ route('dashboard.buscar-formularios', 'Aplazado') }}?periodo={{ $calendarioId }}">
                      <span>Ver formularios aplazados</span>
                      <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                    </a>
                  </div>
                </div>
              </div>                           
              <!-- Fin tarjeta Aplazados -->            

            <!-- Tarjeta de cancelados -->
            <div class="col-sm-6 col-xxl-4">

              <div class="block block-rounded d-flex flex-column h-100 mb-0">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                  <dl class="mb-0">
                    <dt class="fs-3 fw-bold">{{ $data['totalCancelados'] }}</dt>
                    <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Cursos cancelados</dd>
                  </dl>
                  <div class="item item-rounded-lg bg-body-light">
                    <i class="far fa-calendar-xmark fs-3 text-primary"></i>
                  </div>
                </div>
                <div class="bg-body-light rounded-bottom">
                  <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ route('grupos.estado-cursos', ['tipo' => 'cancelados']) }}?periodo={{ $calendarioId }}">
                    <span>Ver cursos cancelados</span>
                    <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                  </a>
                </div>
              </div>
              
            </div>             
             <!-- Fin tarjeta de cancelados --> 
              
             
            <!-- Tarjeta de cerrados -->
            <div class="col-sm-6 col-xxl-4">

              <div class="block block-rounded d-flex flex-column h-100 mb-0">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                  <dl class="mb-0">
                    <dt class="fs-3 fw-bold">{{ $data['totalCursosSinCupos'] }}</dt>
                    <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Cursos cerrados</dd>
                  </dl>
                  <div class="item item-rounded-lg bg-body-light">
                    <i class="far fa-calendar-xmark fs-3 text-primary"></i>
                  </div>
                </div>
                <div class="bg-body-light rounded-bottom">
                  <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ route('grupos.estado-cursos', ['tipo' => 'cerrado']) }}?periodo={{ $calendarioId }}">
                    <span>Ver cursos cerrados</span>
                    <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                  </a>
                </div>
              </div>
              
            </div>             
             <!-- Fin tarjeta de cerrados -->              
          
              </div>
            </div>
        </div>  
        
        <div class="col-xl-12">
          @php

              $participantesHombres = 0;
              $participantesMujeres = 0;
              $participantesOtrosGeneros = 0;
              
              if ($data["totalInscripcionesLegalizadas"] == 0) 
              {
                $data["totalInscripcionesLegalizadas"] = 1;
              }

              $participantesHombres = round(($data['participantesHombres'] / $data["totalInscripcionesLegalizadas"]) * 100);
              $participantesMujeres = round(($data['participantesMujeres'] / $data["totalInscripcionesLegalizadas"]) * 100);;          
              $participantesOtrosGeneros = round(($data['participantesOtrosGeneros'] / $data["totalInscripcionesLegalizadas"]) * 100);

              @endphp


              <!-- Estadísticas Sexo -->
              <!-- <div class="block block-rounded text-center">

                <div class="block-content block-content-full">
                  <div class="row">
                    <div class="col-4">
                      
                      <div class="js-pie-chart pie-chart fw-bold" 
                           data-percent="{{ $participantesHombres }}" 
                           data-line-width="3" 
                           data-size="90" 
                           data-bar-color="#82b54b" 
                           data-track-color="#e9e9e9">
                        <span> {{ $participantesHombres }}%</span>
                      </div>
                      <p class="fs-sm fw-medium text-muted mt-2 mb-0">
                      {{ $data["participantesHombres"] }} participante(s) hombre(s)
                      </p>
                    </div>
                    <div class="col-4">
                      
                      <div class="js-pie-chart pie-chart fw-bold" 
                           data-percent="{{ $participantesMujeres }}" 
                           data-line-width="3" 
                           data-size="90" 
                           data-bar-color="#e04f1a" 
                           data-track-color="#e9e9e9">
                      <span> {{ $participantesMujeres }}%</span>
                      </div>
                      <p class="fs-sm fw-medium text-muted mt-2 mb-0">
                      {{ $data["participantesMujeres"] }} participante(s) mujere(s)
                      </p>
                    </div>
                    <div class="col-4">
                      
                      <div class="js-pie-chart pie-chart fw-bold" 
                            data-percent="{{ $participantesOtrosGeneros }}" 
                            data-line-width="3" 
                            data-size="90" 
                            data-bar-color="#ffb119" 
                            data-track-color="#e9e9e9">
                      <span> {{ $participantesOtrosGeneros }}%</span>
                      </div>
                      <p class="fs-sm fw-medium text-muted mt-2 mb-0">
                      {{ $data["participantesOtrosGeneros"] }} participante(s) otros géneros
                      </p>
                    </div>
                  </div>
                </div>
              </div>   -->
              <!-- Fin Estadística Sexo -->
        </div> 


          <div class="block block-rounded text-center">

          <div class="block block-rounded">
            <div class="block-header block-header-default">
              <h3 class="block-title">Recaudo por áreas</h3>
            </div>
            <div class="block-content">
              <div class="table-responsive">
                <table class="table table-bordered table-vcenter">
                  <tbody>
                    @forelse ($data["listaRecaudoPorAreas"]["areas"] as $area)                        
                        <tr class="fs-sm">
                            <td style="text-align: left;"><a class="block-title">{{ $area->nombre }}</a></td>
                            <td class="text-center">$ {{ $area->TOTAL_RECAUDO }} COP</td>
                        </tr>
                    @empty
                        <tr class="fs-sm">
                            <td colspan="2" class="text-center">No se encontraron inscripciones</td>
                        </tr>                    
                    @endforelse
                    <tr class="fs-sm">
                        <td><a class="block-title">Total</a></td>
                        <td class="text-center">$ {{ $data["listaRecaudoPorAreas"]["total_recaudo"] }} COP</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>          

</div>
@endsection