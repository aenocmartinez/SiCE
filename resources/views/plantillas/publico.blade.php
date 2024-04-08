
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <title>{{ config('app.name') }}</title>

    <meta name="description" content="Cursos de Extensión - Universidad Colegio Mayor de Cundinamarca">
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


    <link rel="shortcut icon" href="https://www.unicolmayor.edu.co/_templates/UNICOL-PORTAL-2021/recursos/images/favicon/apple-icon-57x57.png">
    <link rel="icon" type="image/png" sizes="192x192" href="https://www.unicolmayor.edu.co/_templates/UNICOL-PORTAL-2021/recursos/images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="180x180" href="https://www.unicolmayor.edu.co/_templates/UNICOL-PORTAL-2021/recursos/images/favicon/apple-icon-57x57.png">
    
    <link rel="stylesheet" href="{{asset('assets/js/plugins/sweetalert2/sweetalert2.min.css')}}">

    <link rel="stylesheet" id="css-main" href="{{asset('assets/css/oneui.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/js/plugins/flatpickr/flatpickr.min.css')}}">
  </head>

  <body>

    <div id="page-container">

      <!-- Main Container -->
      <main id="main-container">
        <!-- Page Content -->
        <div class="bg-primary">
          <div class="row g-0 bg-primary-dark-op">
            <!-- Meta Info Section -->
            <div class="hero-static col-lg-3 d-none d-lg-flex flex-column justify-content-center">
              <div class="p-4 p-xl-5 flex-grow-1 d-flex align-items-center">
                <div class="w-100">
                  <a class="link-fx fw-semibold fs-2 text-white" href="#">
                    @yield('nameSection')                    
                  </a>
                  <p class="text-white-75 me-xl-4 mt-2">
                    @yield('description')                  
                  </p>
                </div>
              </div>
              <div class="p-4 p-xl-5 d-xl-flex justify-content-between align-items-center fs-sm">
                <p class="fw-medium text-white-50 mb-0">
                  Universidad Colegio Mayor de Cundinamarca &copy; <span data-toggle="year-copy"></span>
                </p>

              </div>
            </div>
            <!-- END Meta Info Section -->

            <!-- Main Section -->
            <div class="hero-static col-lg-9 d-flex flex-column align-items-center bg-body-extra-light">
              <div class="p-4 w-100 flex-grow-1 d-flex align-items-center">
                <div class="w-100">                 

                  <div class="row g-0 justify-content-center">
                    
                      @yield('content')
                    
                  </div>
                  <!-- END Reminder Form -->
                </div>
              </div>

            </div>
            <!-- END Main Section -->
          </div>
        </div>
        <!-- END Page Content -->
      </main>
      <!-- END Main Container -->
    </div>
    <!-- END Page Container -->

    <!--
        OneUI JS

        Core libraries and functionality
        webpack is putting everything together at assets/_js/main/app.js
    -->
    <script src="{{asset('assets/js/oneui.app.min.js')}}"></script>

    <!-- jQuery (required for jQuery Validation plugin) -->
    <script src="{{asset('assets/js/lib/jquery.min.js')}}"></script>

    <script src="{{asset('assets/js/plugins/sweetalert2/sweetalert2.min.js')}}"></script>

    <!-- Page JS Plugins -->
    <script src="{{asset('assets/js/plugins/jquery-validation/jquery.validate.min.js')}}"></script>

    <!-- Page JS Code -->
    <script src="{{asset('assets/js/pages/op_auth_reminder.min.js')}}"></script>

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
          // One.helpers('jq-notify', {type: '{{ $type }}', icon: '{{ $icon }}', message: "{{ session('status') }}"});
          Swal.fire({
              icon: '{{ $type }}',
              title: "{{ session('status') }}",
              text: "",
              confirmButtonText: 'Aceptar'
            });        
        </script>            
      @endif
          
  </body>
</html>
