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
                <div class="col-6 col-md-4">
                  <div class="fw-semibold text-dark mb-1"># participantes únicos</div>
                  <a class="link-fx fs-3 text-primary" href="javascript:void(0)">{{ $data["numeroParticipantesUnicos"] }}</a>
                </div>
                <div class="col-6 col-md-4">
                  <div class="fw-semibold text-dark mb-1"># form. matriculados</div>
                  <a class="link-fx fs-3 text-primary" href="javascript:void(0)">{{ $data["totalParticipantes"] }}</a>
                </div>                
                <div class="col-6 col-md-4">
                  <div class="fw-semibold text-dark mb-1"># Parts. convenio</div>
                  <a class="link-fx fs-3 text-primary" href="javascript:void(0)">{{ $data["participantesConvenio"] }}</a>
                </div>                
              </div>
            </div>

            <div class="block-content bg-body-light text-center">
              <div class="row items-push text-uppercase">         
                <div class="col-6 col-md-6">
                  <div class="fw-semibold text-dark mb-1">Total ingresos</div>
                  <a class="link-fx fs-3 text-primary" href="javascript:void(0)">${{ $data["totalIngresos"] }} COP</a>
                </div>
                <div class="col-6 col-md-6">
                  <div class="fw-semibold text-dark mb-1">Ing. por convenio</div>
                  <a class="link-fx fs-3 text-primary" href="javascript:void(0)">${{ $data['ingresosConvenio'] }} COP</a>
                </div>
              </div>
            </div>

            <div class="block-content bg-body-light text-center">
              <div class="row items-push text-uppercase">         
                <div class="col-6 col-md-6">
                  <div class="fw-semibold text-dark mb-1">Inscripciones en oficina</div>
                  <a class="link-fx fs-3 text-primary" href="javascript:void(0)">{{ $data["totalFormularioInscritosEnOficina"] }}</a>
                </div>
                <div class="col-6 col-md-6">
                  <div class="fw-semibold text-dark mb-1">Inscripciones formulario público</div>
                  <a class="link-fx fs-3 text-primary" href="javascript:void(0)">{{ $data['totalFormularioInscritosEnLinea'] }}</a>
                </div>
              </div>
            </div>            

          </div>  
          
        
          <div class="col-xl-12">
            @php
            $participantesHombres = 0;
            $participantesMujeres = 0;
            $participantesOtrosGeneros = 0;

            if ($data['numeroParticipantesUnicos'] > 0 ) {
              $participantesHombres = ($data['participantesHombres'] / $data['numeroParticipantesUnicos']) * 100;
              $participantesHombres = round($participantesHombres);
  
              $participantesMujeres = ($data['participantesMujeres'] / $data['numeroParticipantesUnicos']) * 100;
              $participantesMujeres = round($participantesMujeres);
              
              $participantesOtrosGeneros = ($data['participantesOtrosGeneros'] / $data['numeroParticipantesUnicos']) * 100;
              $participantesOtrosGeneros = round($participantesOtrosGeneros);            
            }

            @endphp
              <div class="block block-rounded text-center">

                <div class="block-content block-content-full">
                  <div class="row">
                    <div class="col-4">
                      <!-- Pie Chart Container -->
                      <div class="js-pie-chart pie-chart fw-bold" data-percent="{{ $participantesHombres }}" data-line-width="3" data-size="70" data-bar-color="#82b54b" data-track-color="#e9e9e9">
                        <span> {{ $participantesHombres }}%</span>
                      </div>
                      <p class="fs-sm fw-medium text-muted mt-2 mb-0">
                      {{ $data["participantesHombres"] }} participante(s) hombre(s)
                      </p>
                    </div>
                    <div class="col-4">
                      <!-- Pie Chart Container -->
                      <div class="js-pie-chart pie-chart fw-bold" data-percent="{{ $participantesMujeres }}" data-line-width="3" data-size="70" data-bar-color="#e04f1a" data-track-color="#e9e9e9">
                      <span> {{ $participantesMujeres }}%</span>
                      </div>
                      <p class="fs-sm fw-medium text-muted mt-2 mb-0">
                      {{ $data["participantesMujeres"] }} participante(s) mujere(s)
                      </p>
                    </div>
                    <div class="col-4">
                      <!-- Pie Chart Container -->
                      <div class="js-pie-chart pie-chart fw-bold" data-percent="{{ $participantesOtrosGeneros }}" data-line-width="3" data-size="70" data-bar-color="#ffb119" data-track-color="#e9e9e9">
                      <span> {{ $participantesOtrosGeneros }}%</span>
                      </div>
                      <p class="fs-sm fw-medium text-muted mt-2 mb-0">
                      {{ $data["participantesOtrosGeneros"] }} participante(s) otros géneros
                      </p>
                    </div>
                  </div>
                </div>
              </div>                 
          </div>


          <div class="block block-rounded text-center">

          <div class="block-content block-content-full">
            <div class="row">
              <div class="col-12">    
                <a href="{{ route('calendario.descargar-participantes', $calendarioId) }}" type="button" class="btn btn-lg rounded-pill btn-alt-success px-4 me-1 mb-3">
                  <i class="fa fa-download me-1"></i> Descargar los participantes del periodo
                </a>
            </div>
            </div>
          </div>

          <div class="block block-rounded">
            <div class="block-header block-header-default">
              <h3 class="block-title">Top 5 de cursos con más inscripciones</h3>
            </div>
            <div class="block-content">
              <div class="table-responsive">
                <table class="table table-bordered table-vcenter">
                  <tbody>
                    @forelse ($data["topCursosInscritos"] as $curso)                        
                        <tr class="fs-sm">
                            <td><a class="block-title">{{ $curso['nombre'] }}</a></td>
                            <td class="text-center">{{ $curso['total'] }}</td>
                        </tr>
                    @empty
                        <tr class="fs-sm">
                            <td colspan="2" class="text-center">No se encontraron inscripciones</td>
                        </tr>                    
                    @endforelse

                  </tbody>
                </table>
              </div>
            </div>
          </div>          

</div>
@endsection