@extends("plantillas.principal")

@section("title", "Dashboard")
@section("description", "Információn de interés acerca del actual periodo académico")

@section("seccion", "Dashboard")
@section("subseccion", "Datos general")

@section("content")


<div class="row items-push">
            <div class="col-sm-6 col-xxl-3">
              <!-- Pending Orders -->
              <div class="block block-rounded d-flex flex-column h-100 mb-0">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                  <dl class="mb-0">
                    <dt class="fs-3 fw-bold">32</dt>
                    <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Participantes matriculados</dd>
                  </dl>
                  <div class="item item-rounded-lg bg-body-light">
                    <i class="far fa-gem fs-3 text-primary"></i>
                  </div>
                </div>
                <div class="bg-body-light rounded-bottom">
                  <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="javascript:void(0)">
                    <span>Ver matriculados</span>
                    <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                  </a>
                </div>
              </div>
              <!-- END Pending Orders -->
            </div>
            <div class="col-sm-6 col-xxl-3">
              <!-- New Customers -->
              <div class="block block-rounded d-flex flex-column h-100 mb-0">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                  <dl class="mb-0">
                    <dt class="fs-3 fw-bold">124</dt>
                    <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Total de inscripciones</dd>
                  </dl>
                  <div class="item item-rounded-lg bg-body-light">
                    <i class="far fa-user-circle fs-3 text-primary"></i>
                  </div>
                </div>
                <div class="bg-body-light rounded-bottom">
                  <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="javascript:void(0)">
                    <span>Ver las inscripciones</span>
                    <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                  </a>
                </div>
              </div>
              <!-- END New Customers -->
            </div>
            <div class="col-sm-6 col-xxl-3">
              <!-- Messages -->
              <div class="block block-rounded d-flex flex-column h-100 mb-0">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                  <dl class="mb-0">
                    <dt class="fs-3 fw-bold">45</dt>
                    <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Participantes por convenio</dd>
                  </dl>
                  <div class="item item-rounded-lg bg-body-light">
                    <i class="far fa-paper-plane fs-3 text-primary"></i>
                  </div>
                </div>
                <div class="bg-body-light rounded-bottom">
                  <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="javascript:void(0)">
                    <span>Ver convenios</span>
                    <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                  </a>
                </div>
              </div>
              <!-- END Messages -->
            </div>
            <div class="col-sm-6 col-xxl-3">
              <!-- Conversion Rate -->
              <div class="block block-rounded d-flex flex-column h-100 mb-0">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                  <dl class="mb-0">
                    <dt class="fs-3 fw-bold">4.5%</dt>
                    <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Crecimiento inscripciones</dd>
                  </dl>
                  <div class="item item-rounded-lg bg-body-light">
                    <i class="fa fa-chart-bar fs-3 text-primary"></i>
                  </div>
                </div>
                <div class="bg-body-light rounded-bottom">
                  <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="javascript:void(0)">
                    <span>Ver más estadísticas</span>
                    <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                  </a>
                </div>
              </div>
              <!-- END Conversion Rate-->
            </div>
          </div>
          
          
          
          <div class="row ">

            <!-- <div class="col-md-6 col-xl-3">
              <a class="block block-rounded block-link-shadow bg-primary" href="javascript:void(0)">
                <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                  <div>
                    <i class="fa fa-2x fa-arrow-alt-circle-up text-white-50"></i>
                  </div>
                  <dl class="ms-3 text-end mb-0">
                    <dt class="text-white h3 fw-extrabold mb-0">
                      $18,632
                    </dt>
                    <dd class="text-white fs-sm fw-medium text-muted mb-0">
                      Earnings
                    </dd>
                  </dl>
                </div>
              </a>
            </div> -->
            <!-- <div class="col-md-6 col-xl-3">
              <a class="block block-rounded block-link-shadow bg-success" href="javascript:void(0)">
                <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                  <div>
                    <i class="far fa-2x fa-user text-white-50"></i>
                  </div>
                  <dl class="ms-3 text-end mb-0">
                    <dt class="text-white h3 fw-extrabold mb-0">
                      4,962
                    </dt>
                    <dd class="text-white fs-sm fw-medium text-muted mb-0">
                      Earnings
                    </dd>
                  </dl>
                </div>
              </a>
            </div> -->
            <!-- <div class="col-md-6 col-xl-3">
              <a class="block block-rounded block-link-shadow bg-danger" href="javascript:void(0)">
                <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                  <dl class="me-3 mb-0">
                    <dt class="text-white h3 fw-extrabold mb-0">
                      1,258
                    </dt>
                    <dd class="text-white fs-sm fw-medium text-muted mb-0">
                      Sales
                    </dd>
                  </dl>
                  <div>
                    <i class="fa fa-2x fa-chart-line text-white-50"></i>
                  </div>
                </div>
              </a>
            </div> -->
            <!-- <div class="col-md-6 col-xl-3">
              <a class="block block-rounded block-link-shadow bg-warning" href="javascript:void(0)">
                <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                  <dl class="me-3 mb-0">
                    <dt class="text-white h3 fw-extrabold mb-0">
                      250
                    </dt>
                    <dd class="text-white fs-sm fw-medium text-muted mb-0">
                      Projects
                    </dd>
                  </dl>
                  <div>
                    <i class="fa fa-2x fa-boxes text-white-50"></i>
                  </div>
                </div>
              </a>
            </div> -->
            <div class="col-md-6 mt-6">
              <a class="block block-rounded block-link-shadow" href="javascript:void(0)">
                <div class="block-content block-content-full">
                  <div class="row text-center">
                    <div class="col-4 border-end">
                      <div class="py-3">
                        <div class="item item-circle bg-body-light mx-auto">
                          <i class="fa fa-briefcase text-primary"></i>
                        </div>
                        <dl class="mb-0">
                          <dt class="h3 fw-extrabold mt-3 mb-0">
                            2
                          </dt>
                          <dd class="fs-sm fw-medium text-muted mb-0">
                            Nuevos cursos
                          </dd>
                        </dl>
                      </div>
                    </div>
                    <div class="col-4 border-end">
                      <div class="py-3">
                        <div class="item item-circle bg-body-light mx-auto">
                          <i class="fa fa-chart-line text-primary"></i>
                        </div>
                        <dl class="mb-0">
                          <dt class="h3 fw-extrabold mt-3 mb-0">
                            5
                          </dt>
                          <dd class="fs-sm fw-medium text-muted mb-0">
                            Cursos menos matriculados
                          </dd>
                        </dl>
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="py-3">
                        <div class="item item-circle bg-body-light mx-auto">
                          <i class="fa fa-users text-primary"></i>
                        </div>
                        <dl class="mb-0">
                          <dt class="h3 fw-extrabold mt-3 mb-0">
                            83
                          </dt>
                          <dd class="fs-sm fw-medium text-muted mb-0">
                            Participantes recurrentes
                          </dd>
                        </dl>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-md-6 mt-6">
              <a class="block block-rounded bg-danger" href="javascript:void(0)">
                <div class="block-content block-content-full">
                  <div class="row text-center">
                    <div class="col-4 border-end border-black-op">
                      <div class="py-3">
                        <div class="item item-circle bg-black-25 mx-auto">
                          <i class="fa fa-briefcase text-white"></i>
                        </div>
                        <dl class="mb-0">
                          <dt class="text-white h3 fw-extrabold mt-3 mb-0">
                            85
                          </dt>
                          <dd class="text-white fs-sm fw-medium text-muted mb-0">
                            Cursos
                          </dd>
                        </dl>
                      </div>
                    </div>
                    <div class="col-4 border-end border-black-op">
                      <div class="py-3">
                        <div class="item item-circle bg-black-25 mx-auto">
                          <i class="fa fa-chart-line text-white"></i>
                        </div>
                        <dl class="mb-0">
                          <dt class="text-white h3 fw-extrabold mt-3 mb-0">
                            10
                          </dt>
                          <dd class="text-white fs-sm fw-medium text-muted mb-0">
                            Más matriculados
                          </dd>
                        </dl>
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="py-3">
                        <div class="item item-circle bg-black-25 mx-auto">
                          <i class="fa fa-users text-white"></i>
                        </div>
                        <dl class="mb-0">
                          <dt class="text-white h3 fw-extrabold mt-3 mb-0">
                            96
                          </dt>
                          <dd class="text-white fs-sm fw-medium text-muted mb-0">
                            Instructores
                          </dd>
                        </dl>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          </div>          

@endsection