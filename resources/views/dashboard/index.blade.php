@extends("plantillas.principal")

@section("title", "Dashboard")
@section("description", "Információn de interés acerca del actual periodo académico")

@section("seccion", "Dashboard")
@section("subseccion", "Datos general")

@section("content")


<div class="row items-push">
            <div class="col-sm-6 col-xxl-3">
              <!-- New Customers -->
              <div class="block block-rounded d-flex flex-column h-100 mb-0">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                  <dl class="mb-0">
                    <dt class="fs-3 fw-bold">{{ $datosDashboard['totalRevisionesPago'] }}</dt>
                    <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Revisar comprobante de pago</dd>
                  </dl>
                  <div class="item item-rounded-lg bg-body-light">
                    <i class="far fa-user-circle fs-3 text-primary"></i>
                  </div>
                </div>
                <div class="bg-body-light rounded-bottom">
                  <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ route('dashboard.buscar-formularios', 'Revisar comprobante de pago') }}">
                    <span>Ir a revisar</span>
                    <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                  </a>
                </div>
              </div>
              
            </div>
            <div class="col-sm-6 col-xxl-3">
              
              <div class="block block-rounded d-flex flex-column h-100 mb-0">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                  <dl class="mb-0">
                    <dt class="fs-3 fw-bold">{{ $datosDashboard['totalMatriculados'] }}</dt>
                    <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Formularios matriculados</dd>
                  </dl>
                  <div class="item item-rounded-lg bg-body-light">
                    <i class="far fa-gem fs-3 text-primary"></i>
                  </div>
                </div>
                <div class="bg-body-light rounded-bottom">
                  <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ route('dashboard.buscar-formularios', 'Pagado') }}">
                    <span>Ver matriculados</span>
                    <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                  </a>
                </div>
              </div>
             
            </div>
            <div class="col-sm-6 col-xxl-3">
              
              <div class="block block-rounded d-flex flex-column h-100 mb-0">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                  <dl class="mb-0">
                    <dt class="fs-3 fw-bold">{{ $datosDashboard['totalPendintesDePago'] }}</dt>
                    <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Pendientes por legalizar</dd>
                  </dl>
                  <div class="item item-rounded-lg bg-body-light">
                    <i class="fa fa-chart-bar fs-3 text-primary"></i>
                  </div>
                </div>
                <div class="bg-body-light rounded-bottom">
                  <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ route('dashboard.buscar-formularios', 'Pendiente de pago') }}">
                    <span>Ver pendientes</span>
                    <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                  </a>
                </div>
              </div>
              
            </div>                        
            <div class="col-sm-6 col-xxl-3">

              <div class="block block-rounded d-flex flex-column h-100 mb-0">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                  <dl class="mb-0">
                    <dt class="fs-3 fw-bold">{{ $datosDashboard['totalAnulados'] }}</dt>
                    <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Anulados</dd>
                  </dl>
                  <div class="item item-rounded-lg bg-body-light">
                    <i class="far fa-paper-plane fs-3 text-primary"></i>
                  </div>
                </div>
                <div class="bg-body-light rounded-bottom">
                  <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ route('dashboard.buscar-formularios', 'Anulado') }}">
                    <span>Ver anulados</span>
                    <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                  </a>
                </div>
              </div>
              
            </div>

            <!-- Tarjeta de cerrados -->
            <div class="col-sm-6 col-xxl-3">

              <div class="block block-rounded d-flex flex-column h-100 mb-0">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                  <dl class="mb-0">
                    <dt class="fs-3 fw-bold">{{ $datosDashboard['totalCursosSinCupos'] }}</dt>
                    <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Cerrados</dd>
                  </dl>
                  <div class="item item-rounded-lg bg-body-light">
                    <i class="far fa-bell-slash fs-3 text-primary"></i>
                  </div>
                </div>
                <div class="bg-body-light rounded-bottom">
                  <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ route('grupos.estado-cursos', ['tipo' => 'cerrado']) }}">
                    <span>Ver cerrados</span>
                    <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                  </a>
                </div>
              </div>
              
            </div>             
             <!-- Fin tarjeta de cerrados -->

            <!-- Tarjeta de cancelados -->
            <div class="col-sm-6 col-xxl-3">

              <div class="block block-rounded d-flex flex-column h-100 mb-0">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                  <dl class="mb-0">
                    <dt class="fs-3 fw-bold">{{ $datosDashboard['totalCancelados'] }}</dt>
                    <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Cancelados</dd>
                  </dl>
                  <div class="item item-rounded-lg bg-body-light">
                    <i class="far fa-calendar-xmark fs-3 text-primary"></i>
                  </div>
                </div>
                <div class="bg-body-light rounded-bottom">
                  <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ route('grupos.estado-cursos', ['tipo' => 'cancelados']) }}">
                    <span>Ver cancelados</span>
                    <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                  </a>
                </div>
              </div>
              
            </div>             
             <!-- Fin tarjeta de cancelados -->             

             <!-- Tarjeta Aplazados -->
             <div class="col-sm-6 col-xxl-3">
                <div class="block block-rounded d-flex flex-column h-100 mb-0">
                  <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                      <dt class="fs-3 fw-bold">{{ $datosDashboard['totalAplazados'] }}</dt>
                      <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Aplazados</dd>
                    </dl>
                    <div class="item item-rounded-lg bg-body-light">
                    <i class="fas fa-history fs-3 text-primary"></i>
                    </div>
                  </div>
                  <div class="bg-body-light rounded-bottom">
                    <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ route('dashboard.buscar-formularios', 'Aplazado') }}">
                      <span>Ver aplazados</span>
                      <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                    </a>
                  </div>
                </div>
              </div>                           
              <!-- Fin tarjeta Aplazados -->

             <!-- Tarjeta Devolución -->
             <div class="col-sm-6 col-xxl-3">
                <div class="block block-rounded d-flex flex-column h-100 mb-0">
                  <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                      <dt class="fs-3 fw-bold">{{ $datosDashboard['totalDevolucion'] }}</dt>
                      <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Devolución</dd>
                    </dl>
                    <div class="item item-rounded-lg bg-body-light">
                    <i class="fas fa-history fs-3 text-primary"></i>
                    </div>
                  </div>
                  <div class="bg-body-light rounded-bottom">
                    <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="{{ route('dashboard.buscar-formularios', 'Devuelto') }}">
                      <span>Ver devueltos</span>
                      <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                    </a>
                  </div>
                </div>
              </div>                           
              <!-- Fin tarjeta Devolución -->

          </div>
          
          
          
          <div class="row mt-3">

            <div class="col-md-6 col-xl-3">
              <a class="block block-rounded block-link-shadow bg-primary" href="javascript:void(0)">
                <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                  <div>
                    <i class="fa fa-2x fa-arrow-alt-circle-up text-white-50"></i>
                  </div>
                  <dl class="ms-3 text-end mb-0">
                    <dt class="text-white h3 fw-medium mb-0">
                      {{ $datosDashboard['pagoSinDescuento'] }} <!-- <span class="fs-sm fw-light mb-0">COP</span> -->
                    </dt>
                    <dd class="text-white fs-sm fw-medium text-muted mb-0">
                      Total sin descuento
                    </dd>
                  </dl>
                </div>
              </a>
            </div>
            <div class="col-md-6 col-xl-3">
              <a class="block block-rounded block-link-shadow bg-success" href="javascript:void(0)">
                <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                  <div>
                    <i class="far fa-2x fa-user text-white-50"></i>
                  </div>
                  <dl class="ms-3 text-end mb-0">
                    <dt class="text-white h3 fw-medium mb-0">
                    {{ $datosDashboard['pagoPorConvenio'] }} <!-- <span class="fs-sm fw-light mb-0">COP</span> -->
                    </dt>
                    <dd class="text-white fs-sm fw-medium text-muted mb-0">
                      Total por convenio
                    </dd>
                  </dl>
                </div>
              </a>
            </div>
            <div class="col-md-6 col-xl-3">
              <a class="block block-rounded block-link-shadow bg-danger" href="javascript:void(0)">
                <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                  <dl class="me-3 mb-0">
                    <dt class="text-white h3 fw-medium mb-0">
                    ${{ $datosDashboard['pagoPendientes'] }} 
                    </dt>
                    <dd class="text-white fs-sm fw-medium text-muted mb-0">
                      Pendientes de pago
                    </dd>
                  </dl>
                  <div>
                    <i class="fa fa-2x fa-chart-line text-white-50"></i>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-md-6 col-xl-3">
              <a class="block block-rounded block-link-shadow bg-warning" href="javascript:void(0)">
                <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                  <dl class="me-3 mb-0">
                    <dt class="text-white h3 fw-extrabold mb-0">
                    {{ $datosDashboard['pagoTotal'] }} <!-- <span class="fs-sm fw-light mb-0">COP</span> -->
                    </dt>
                    <dd class="text-white fs-sm fw-medium text-muted mb-0">
                      Total recaudado <span class="fs-sm fw-light mb-0">(sin descuento + convenio)</span>
                    </dd>
                  </dl>
                  <!-- <div>
                    <i class="fa fa-2x fa-boxes text-white-50"></i>
                  </div> -->
                </div>
              </a>
            </div>
            <div class="col-md-6 mt-4">

            </div>

          </div>          

@endsection