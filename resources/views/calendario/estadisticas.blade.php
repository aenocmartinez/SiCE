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

    <!-- <div class="row">
            <div class="col-6">
              <a class="block block-rounded block-link-shadow text-center" href="javascript:void(0)">
                <div class="block-content block-content-full">
                  <div class="fs-2 fw-semibold text-dark">
                    <i class="fa fa-pencil-alt"></i>
                  </div>
                </div>
                <div class="block-content py-2 bg-body-light">
                  <p class="fw-medium fs-sm text-muted mb-0">
                    Edit Customer
                  </p>
                </div>
              </a>
            </div>
            <div class="col-6">
              <a class="block block-rounded block-link-shadow text-center" href="javascript:void(0)">
                <div class="block-content block-content-full">
                  <div class="fs-2 fw-semibold text-danger">
                    <i class="fa fa-times"></i>
                  </div>
                </div>
                <div class="block-content py-2 bg-body-light">
                  <p class="fw-medium fs-sm text-danger mb-0">
                    Remove Customer
                  </p>
                </div>
              </a>
            </div>

    </div> -->

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
                  <div class="fw-semibold text-dark mb-1"># participantes</div>
                  <a class="link-fx fs-3 text-primary" href="javascript:void(0)">{{ $data["totalParticipantes"]}}</a>
                </div>
                <div class="col-6 col-md-4">
                  <div class="fw-semibold text-dark mb-1">Total ingresos</div>
                  <a class="link-fx fs-3 text-primary" href="javascript:void(0)">${{ $data["totalIngresos"] }} COP</a>
                </div>
                <div class="col-6 col-md-4">
                  <div class="fw-semibold text-dark mb-1"># Cursos abiertos</div>
                  <a class="link-fx fs-3 text-primary" href="javascript:void(0)">{{ $data['numeroCursos'] }}</a>
                </div>
                <!-- <div class="col-6 col-md-3">
                  <div class="fw-semibold text-dark mb-1"># Participantes</div>
                  <a class="link-fx fs-3 text-primary" href="javascript:void(0)">{{ $data["totalParticipantes"]}}</a>
                </div> -->
              </div>
            </div>
          </div>  
          
          
          <div class="block block-rounded">
            <!-- <div class="block-header block-header-default">
              <h3 class="block-title">Addresses (2)</h3>
            </div> -->
            <div class="block-content">
              <div class="row">
                <div class="col-lg-6">
                  <!-- Billing Address -->
                  <div class="block block-rounded block-bordered">
                    <div class="block-header border-bottom">
                      <h3 class="block-title text-center">Participantes Hombres</h3>
                    </div>
                    <div class="block-content">
                      <div class="fs-4 mb-1 text-center">{{ $data["participantesHombres"] }}</div>
                      <!-- <address class="fs-sm">
                        Sunrise Str 620<br>
                        Melbourne<br>
                        Australia, 11-587<br><br>
                        <i class="fa fa-phone"></i> (999) 888-55555<br>
                        <i class="fa fa-envelope-o"></i> <a href="javascript:void(0)">company@example.com</a>
                      </address> -->
                    </div>
                  </div>
                  <!-- END Billing Address -->
                </div>
                <div class="col-lg-6">
                  <!-- Shipping Address -->
                  <div class="block block-rounded block-bordered">
                    <div class="block-header border-bottom">
                      <h3 class="block-title text-center">Participantes Mujeres</h3>
                    </div>
                    <div class="block-content">
                      <div class="fs-4 mb-1 text-center">{{ $data["participantesMujeres"] }}</div>
                      <!-- <address class="fs-sm">
                        Sunrise Str 620<br>
                        Melbourne<br>
                        Australia, 11-587<br><br>
                        <i class="fa fa-phone"></i> (999) 888-55555<br>
                        <i class="fa fa-envelope-o"></i> <a href="javascript:void(0)">company@example.com</a>
                      </address> -->
                    </div>
                  </div>
                  <!-- END Shipping Address -->
                </div>
              </div>
            </div>
          </div>
          
        
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
            <div class="block-header block-header-default">
              <h3 class="block-title text-center">Top 5 de cursos con más inscripciones</h3>
            </div>
            <div class="block-content">
              <div class="table-responsive">
                <table class="table table-bordered table-vcenter">
                  <tbody>
                    @foreach ($data["topCursosInscritos"] as $curso)                        
                        <tr class="fs-sm">
                            <td><a class="block-title">{{ $curso['nombre'] }}</a></td>
                            <td class="text-center">{{ $curso['total'] }}</td>
                        </tr>
                    @endforeach

                  </tbody>
                </table>
              </div>
            </div>
          </div>          

</div>
@endsection