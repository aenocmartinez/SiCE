@extends("plantillas.principal")

@php
    $titulo = "Más información del grupo";
@endphp

@section("title", $titulo)

@section("description", "")

@section("seccion")
    <a class="link-fx" href="{{ route('grupos.index') }}">
        Grupos
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")
<div class="block block-rounded">

    <div class="block-content">
          
        
          <!-- <div class="block block-rounded">
            <div class="block-header block-header-default">
              <h3 class="block-title">Referred Members (3)</h3>
            </div>
            <div class="block-content">
              <div class="row items-push">
                <div class="col-md-4">
                  
                  <a class="block block-rounded block-bordered block-link-shadow h-100 mb-0" href="javascript:void(0)">
                    <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                      <div>
                        <div class="fw-semibold mb-1">Susan Day</div>
                        <div class="fs-sm text-muted">4 Orders</div>
                      </div>
                      <div class="ms-3">
                        <img class="img-avatar" src="{{asset('assets/media/avatars/avatar1.jpg')}}" alt="">
                      </div>
                    </div>
                  </a>
                  
                </div>
                <div class="col-md-4">
                  
                  <a class="block block-rounded block-bordered block-link-shadow h-100 mb-0" href="javascript:void(0)">
                    <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                      <div>
                        <div class="fw-semibold mb-1">David Fuller</div>
                        <div class="fs-sm text-muted">5 Orders</div>
                      </div>
                      <div class="ms-3">
                        <img class="img-avatar" src="{{asset('assets/media/avatars/avatar12.jpg')}}" alt="">
                      </div>
                    </div>
                  </a>
                  
                </div>
                <div class="col-md-4">
                  
                  <a class="block block-rounded block-bordered block-link-shadow h-100 mb-0" href="javascript:void(0)">
                    <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                      <div>
                        <div class="fw-semibold mb-1">Lisa Jenkins</div>
                        <div class="fs-sm text-muted">3 Orders</div>
                      </div>
                      <div class="ms-3">
                        <img class="img-avatar" src="{{asset('assets/media/avatars/avatar7.jpg')}}" alt="">
                      </div>
                    </div>
                  </a>
                  
                </div>
              </div>
            </div>
          </div>           -->


          <div class="block block-rounded">
            <div class="block-content">
              <div class="table-responsive">
                <table class="table table-bordered table-vcenter">
                  <tbody>
                        <tr class="fs-sm">
                            <td>Código</td>
                            <td>{{ $grupo->getNombre() }}</td>
                        </tr>                    
                        <tr class="fs-sm">
                            <td>Curso</td>
                            <td>{{ $grupo->getNombreCurso() }}</td>
                        </tr>
                        <tr class="fs-sm">
                            <td>Orientador</td>
                            <td>{{ $grupo->getNombreOrientador() }}</td>
                        </tr>                        
                        <tr class="fs-sm">
                            <td>Número de participantes</td>
                            <td>{{ $grupo->getTotalInscritos() }}</td>
                        </tr>                         
                        <tr class="fs-sm">
                            <td>Periodo</td>
                            <td>{{ $grupo->getNombreCalendario() }}</td>
                        </tr>
                        <tr class="fs-sm">
                            <td>Horario</td>
                            <td>{{ $grupo->getDia() }} / {{ $grupo->getJornada() }}</td>
                        </tr>
                        <tr class="fs-sm">
                            <td>Salón</td>
                            <td>{{ $grupo->getNombreSalon() }}</td>
                        </tr>  
                        <tr class="fs-sm">
                            <td>Total de cupo</td>
                            <td>{{ $grupo->getCupo() }}</td>
                        </tr>                                                                                                                       
                  </tbody>
                </table>
              </div>
            </div>
          </div>          

</div>
@endsection