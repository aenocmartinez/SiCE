<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
  
    <title>{{ config('app.name') }}</title>

    <meta name="description" content="OneUI - Bootstrap 5 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Open Graph Meta -->
    <meta property="og:title" content="{{ config('app.name') }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:description" content="{{ config('app.name') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="">

    <link rel="shortcut icon" href="{{asset('assets/media/favicons/favicon.png')}}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{asset('assets/media/favicons/favicon-192x192.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('assets/media/favicons/apple-touch-icon-180x180.png')}}">

    <link rel="stylesheet" href="{{asset('assets/js/plugins/sweetalert2/sweetalert2.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/js/plugins/simplemde/simplemde.min.css')}}">
    
    <link rel="stylesheet" href="{{asset('assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/js/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/js/plugins/ion-rangeslider/css/ion.rangeSlider.css')}}">
    <link rel="stylesheet" href="{{asset('assets/js/plugins/dropzone/min/dropzone.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/js/plugins/flatpickr/flatpickr.min.css')}}">
    
    
    <link rel="stylesheet" id="css-main" href="{{asset('assets/css/oneui.min.css')}}">
  </head>

  <body>
    <div id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow">

      <nav id="sidebar" aria-label="Main Navigation">     
        <!-- Side Header -->
        <div class="content-header mt-3">
          <!-- Logo -->
          <a class="fw-semibold text-dual" href="{{ route('dashboard') }}">
            <span class="smini-visible">
              <i class="fa fa-circle-notch text-primary"></i>
            </span>
            <span class="smini-hide fs-5 tracking-wider">
              Logo              
                <!-- <img src="{{asset('assets/media/favicons/logo_principal.png')}}" 
                     width="30%" 
                     alt="Universidad Colegio Mayor de Cundinamarca"> -->
            </span>
          </a>
          <!-- END Logo -->
        </div>
        <!-- END Side Header -->

        <!-- Sidebar Scrolling -->
        <div class="js-sidebar-scroll">
          <!-- Side Navigation -->
          <div class="content-side">
            <ul class="nav-main">
              <li class="nav-main-item">
                <a class="nav-main-link {{ setActive(['dashboard', 'dashboard.*']) }}" href="{{ route('dashboard') }}">
                  <!-- <i class="nav-main-link-icon si si-speedometer"></i> -->
                  <span class="nav-main-link-name">Dashboard</span>
                </a>
                <a class="nav-main-link {{ setActive(['areas.index', 'areas.*']) }}" href="{{ route('areas.index') }}">
                  <span class="nav-main-link-name">Áreas</span>
                </a>
                <a class="nav-main-link {{ setActive(['cursos.index', 'cursos.*']) }}" href="{{ route('cursos.index','1') }}">
                  <span class="nav-main-link-name">Cursos</span>
                </a>   
                <a class="nav-main-link {{ setActive(['tipo-salones.index', 'tipo-salones.*']) }}" href="{{ route('tipo-salones.index') }}">
                  <span class="nav-main-link-name">Tipo de salones</span>
                </a>                
                <a class="nav-main-link {{ setActive(['salones.index', 'salones.*']) }}" href="{{ route('salones.index') }}">
                  <span class="nav-main-link-name">Salones</span>
                </a>
                <a class="nav-main-link {{ setActive(['orientadores.index', 'orientadores.*']) }}" href="{{ route('orientadores.index') }}">
                  <span class="nav-main-link-name">Orientadores</span>
                </a>
                <a class="nav-main-link {{ setActive(['participantes.index', 'participantes.*']) }}" href="{{ route('participantes.index') }}">
                  <span class="nav-main-link-name">Participantes</span>
                </a>                                
              </li>

              <li class="nav-main-heading">Periodo académico</li>
              <li class="nav-main-item">
                <a class="nav-main-link {{ setActive(['calendario.index', 'calendario.*']) }}" href="{{ route('calendario.index') }}">
                  <span class="nav-main-link-name">Periodo</span>
                </a>            
                <a class="nav-main-link {{ setActive(['grupos.index', 'grupos.*']) }}" href="{{ route('grupos.index') }}">
                  <span class="nav-main-link-name">Grupos</span>
                </a>
                <a class="nav-main-link {{ setActive(['convenios.index', 'convenios.*']) }}" href="{{ route('convenios.index') }}">
                  <span class="nav-main-link-name">Convenios</span>
                </a>
                <a class="nav-main-link {{ setActive(['formulario-inscripcion.paso-1', 'formulario-inscripcion.*']) }}" href="{{ route('formulario-inscripcion.paso-1') }}">
                  <span class="nav-main-link-name">Nueva inscripción</span>
                </a>
                <a class="nav-main-link {{ setActive(['formularios.index', 'formularios.*']) }}" href="{{ route('formularios.index') }}">
                  <span class="nav-main-link-name">Listado de inscritos</span>
                </a>
                <a class="nav-main-link" href="{{ route('home') }}">
                  <span class="nav-main-link-name">Cambios y traslados</span>
                </a>                


              </li>

            </ul>
          </div>
          <!-- END Side Navigation -->
        </div>
        <!-- END Sidebar Scrolling -->
      </nav>
      <!-- END Sidebar -->

      <!-- Header -->
      <header id="page-header">
        <!-- Header Content -->
        <div class="content-header">
          <!-- Left Section -->
          <div class="d-flex align-items-center">
            <!-- Toggle Sidebar -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
            <button type="button" class="btn btn-sm btn-alt-secondary me-2 d-lg-none" data-toggle="layout" data-action="sidebar_toggle">
              <i class="fa fa-fw fa-bars"></i>
            </button>

          </div>
          <!-- END Left Section -->

          <!-- Right Section -->
          <div class="d-flex align-items-center">
            <!-- User Dropdown -->
            <div class="dropdown d-inline-block ms-2">
              <button type="button" class="btn btn-sm btn-alt-secondary d-flex align-items-center" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="rounded-circle" src="{{asset('assets/media/avatars/avatar10.jpg')}}" alt="Header Avatar" style="width: 21px;">
                <span class="d-none d-sm-inline-block ms-2">Abimelec Enoc Martinez Robles</span>
                <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block opacity-50 ms-1 mt-1"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-md dropdown-menu-end p-0 border-0" aria-labelledby="page-header-user-dropdown">
                <div class="p-3 text-center bg-body-light border-bottom rounded-top">
                  <img class="img-avatar img-avatar48 img-avatar-thumb" src="{{asset('assets/media/avatars/avatar10.jpg')}}" alt="">
                  <p class="mt-2 mb-0 fw-medium">{{ "Abimelec Enoc Martínez Robles" }}</p>
                  <p class="mb-0 text-muted fs-sm fw-medium">{{ "Administrador" }}</p>
                </div>
                <!-- PERFIL -->
                <!-- <div class="p-2"> -->
                  <!-- <a class="dropdown-item d-flex align-items-center justify-content-between" href="be_pages_generic_profile.html">
                    <span class="fs-sm fw-medium">Profile</span>
                    <span class="badge rounded-pill bg-primary ms-2">1</span>
                  </a> -->
                <!-- </div> -->
                <div role="separator" class="dropdown-divider m-0"></div>
                <div class="p-2">
                  <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('home') }}">
                    <span class="fs-sm fw-medium">Cerrar sesión</span>
                  </a>
                </div>
              </div>
            </div>
            <!-- END User Dropdown -->

            <!-- Notifications Dropdown -->
            <div class="dropdown d-inline-block ms-2">
              <button type="button" class="btn btn-sm btn-alt-secondary" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-fw fa-bell"></i>
                <span class="text-primary">•</span>
              </button>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 border-0 fs-sm" aria-labelledby="page-header-notifications-dropdown">
                <div class="p-2 bg-body-light border-bottom text-center rounded-top">
                  <h5 class="dropdown-header text-uppercase">Notificaciones</h5>
                </div>
                <ul class="nav-items mb-0">
                  <li>
                    <a class="text-dark d-flex py-2" href="javascript:void(0)">
                      <div class="flex-shrink-0 me-2 ms-3">
                        <i class="fa fa-fw fa-check-circle text-success"></i>
                      </div>
                      <div class="flex-grow-1 pe-2">
                        <div class="fw-semibold">Nueva matrícula legalizada</div>
                        <span class="fw-medium text-muted">Hace 15 minutos</span>
                      </div>
                    </a>
                  </li>
                  <!-- <li>
                    <a class="text-dark d-flex py-2" href="javascript:void(0)">
                      <div class="flex-shrink-0 me-2 ms-3">
                        <i class="fa fa-fw fa-plus-circle text-primary"></i>
                      </div>
                      <div class="flex-grow-1 pe-2">
                        <div class="fw-semibold">1 new sale, keep it up</div>
                        <span class="fw-medium text-muted">22 min ago</span>
                      </div>
                    </a>
                  </li>
                  <li>
                    <a class="text-dark d-flex py-2" href="javascript:void(0)">
                      <div class="flex-shrink-0 me-2 ms-3">
                        <i class="fa fa-fw fa-times-circle text-danger"></i>
                      </div>
                      <div class="flex-grow-1 pe-2">
                        <div class="fw-semibold">Update failed, restart server</div>
                        <span class="fw-medium text-muted">26 min ago</span>
                      </div>
                    </a>
                  </li>
                  <li>
                    <a class="text-dark d-flex py-2" href="javascript:void(0)">
                      <div class="flex-shrink-0 me-2 ms-3">
                        <i class="fa fa-fw fa-plus-circle text-primary"></i>
                      </div>
                      <div class="flex-grow-1 pe-2">
                        <div class="fw-semibold">2 new sales, keep it up</div>
                        <span class="fw-medium text-muted">33 min ago</span>
                      </div>
                    </a>
                  </li> -->
                  <li>
                    <a class="text-dark d-flex py-2" href="javascript:void(0)">
                      <div class="flex-shrink-0 me-2 ms-3">
                        <i class="fa fa-fw fa-user-plus text-success"></i>
                      </div>
                      <div class="flex-grow-1 pe-2">
                        <div class="fw-semibold">Nueva inscripción</div>
                        <span class="fw-medium text-muted">Hace 41 min</span>
                      </div>
                    </a>
                  </li>
                  <!-- <li>
                    <a class="text-dark d-flex py-2" href="javascript:void(0)">
                      <div class="flex-shrink-0 me-2 ms-3">
                        <i class="fa fa-fw fa-check-circle text-success"></i>
                      </div>
                      <div class="flex-grow-1 pe-2">
                        <div class="fw-semibold">You have a new follower</div>
                        <span class="fw-medium text-muted">42 min ago</span>
                      </div>
                    </a>
                  </li> -->
                </ul>
                <!-- <div class="p-2 border-top text-center">
                  <a class="d-inline-block fw-medium" href="javascript:void(0)">
                    <i class="fa fa-fw fa-arrow-down me-1 opacity-50"></i> Load More..
                  </a>
                </div> -->
              </div>
            </div>
            <!-- END Notifications Dropdown -->
          </div>
          <!-- END Right Section -->
        </div>
        <!-- END Header Content -->

      </header>
      <!-- END Header -->

      <!-- Main Container -->
      <main id="main-container">
        <!-- Hero -->
        <div class="bg-body-light">
          <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
              <div class="flex-grow-1">
                <h1 class="fw-light mb-0">
                @yield('title')
                </h1>
                <h2 class="fs-base lh-base fw-normal text-muted mb-5">              
                @yield('description')
                </h2>
              </div>
              <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                  <li class="breadcrumb-item">
                    @yield('seccion')                    
                  </li>
                  <li class="breadcrumb-item" aria-current="page">
                    @yield('subseccion')
                  </li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
        <!-- END Hero -->

        <!-- Page Content -->
        <div class="content">
        @yield('content')
          <!-- <div class="block block-rounded">
            <div class="block-content">
              
              
            </div>
          </div> -->
        </div>
        <!-- END Page Content -->
      </main>
      <!-- END Main Container -->

      <!-- Footer -->
      <footer id="page-footer" class="bg-body-light">
        <div class="content py-3">
          <div class="row fs-sm">
            <div class="col-sm-6 order-sm-2 py-1 text-center text-sm-end">
              <!-- Crafted with <i class="fa fa-heart text-danger"></i> by <a class="fw-semibold" href="https://1.envato.market/ydb" target="_blank">pixelcave</a> -->
              <small>
                Vicerrectoría de Investigación, Innovación y Extensión <br>
                Subdirección de Proyección y Extensión <br>
              </small>
            </div>
            <div class="col-sm-6 order-sm-1 py-1 text-center text-sm-start">
              <a class="fw-semibold" href="{{ route('home') }}">
                <small>
                Cursos de Extensión
                </small>
              </a> &copy; <span data-toggle="year-copy"></span>
            </div>
          </div>
        </div>
      </footer>
      <!-- END Footer -->
    </div>
    <!-- END Page Container -->

    <!--
        OneUI JS

        Core libraries and functionality
        webpack is putting everything together at assets/_js/main/app.js
    -->
    <script src="{{asset('assets/js/oneui.app.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/sweetalert2/sweetalert2.min.js')}}"></script>

    

    <script src="{{asset('assets/js/plugins/chart.js/chart.umd.js')}}"></script>
    <script src="{{asset('assets/js/pages/be_pages_dashboard.min.js')}}"></script>   
    
  
    <script src="{{asset('assets/js/lib/jquery.min.js')}}"></script>

    <script src="{{asset('assets/js/plugins/select2/js/select2.full.min.js')}}"></script>

    <script src="{{asset('assets/js/plugins/easy-pie-chart/jquery.easypiechart.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/jquery-sparkline/jquery.sparkline.min.js')}}"></script>    
    <script>One.helpersOnLoad(['jq-easy-pie-chart', 'jq-sparkline']);</script>    



    <script src="{{asset('assets/js/plugins/bootstrap-notify/bootstrap-notify.min.js')}}"></script>
      @if (session('status'))
        @php
          $icon = 'fa fa-info-circle me-1';
          $type = 'success';
          if (session('code') == '401' || session('code') == '500' || session('code') == '404') {
            $icon = 'fa fa-times me-1';
            $type = 'danger';
          }
        @endphp
        <script>
          One.helpers('jq-notify', {type: '{{ $type }}', icon: '{{ $icon }}', message: "{{ session('status') }}"});
        </script>            
      @endif

      @if (session('nombre_archivo'))
      <script>
          window.location.href = "{{ route('formulario-inscripcion.descargar-formato-pago', [session('nombre_archivo')]) }}";
      </script>
      @endif
  </body>
</html>
