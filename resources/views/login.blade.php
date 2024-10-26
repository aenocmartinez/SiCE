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

    <meta property="og:title" content="{{ config('app.name') }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:description" content="{{ config('app.name') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="">

    <link rel="shortcut icon" href="https://www.unicolmayor.edu.co/_templates/UNICOL-PORTAL-2021/recursos/images/favicon/apple-icon-57x57.png">
    <link rel="icon" type="image/png" sizes="192x192" href="https://www.unicolmayor.edu.co/_templates/UNICOL-PORTAL-2021/recursos/images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="180x180" href="https://www.unicolmayor.edu.co/_templates/UNICOL-PORTAL-2021/recursos/images/favicon/apple-icon-57x57.png">

    <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/oneui.min.css') }}">
    <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/estilos/auth.css') }}">
</head>

<body>
    <div id="page-container">
        <main id="main-container">
            <div class="hero-static d-flex align-items-center">
                <div class="content">
                    <div class="row justify-content-center push">
                        <div class="col-md-8 col-lg-6 col-xl-4">
                            <div class="block block-rounded mb-0">
                                <div class="block-header block-header-default">
                                    <h3 class="block-title">Cursos de extensión</h3>
                                    <div class="block-options">
                                        <i class="fa fa-user"></i>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <div class="p-sm-3 px-lg-4 px-xxl-5 py-lg-5">
                                        <div class="container-logo-principal">
                                            <img class="logo-principal" src="{{ asset('assets/media/favicons/LogoEntidad2.png') }}" 
                                            alt="Universidad Colegio Mayor de Cundinamarca">
                                        </div>
                                        <h1 class="h2 mb-1 text-center">Login</h1>
                                        <p class="fw-medium text-muted text-center"></p>

                                        <form action="{{ route('login') }}" method="POST">
                                            @csrf
                                            <div class="py-3">
                                                <div class="mb-4">
                                                    <input type="email" class="form-control form-control-alt form-control-lg @error('email') is-invalid @enderror" id="email" name="email" autocomplete="off" placeholder="Correo electrónico" value="{{ old('email') }}">
                                                    @error('email')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="mb-4">
                                                    <input type="password" class="form-control form-control-alt form-control-lg @error('password') is-invalid @enderror" id="password" name="password" placeholder="Contraseña">
                                                    @error('password')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror
                                                </div>

                                                <!-- reCAPTCHA -->
                                                <div class="g-recaptcha mb-4 text-center @error('g-recaptcha-response') is-invalid @enderror" data-sitekey="{{ env('RECAPTCHA_SITE_KEY_V2') }}"></div>
                                                @error('g-recaptcha-response')
                                                    <span class="invalid-feedback d-block text-center" role="alert">
                                                        {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="row mb-12">
                                                <div class="col-md-12 col-xl-12">
                                                    <button type="submit" class="btn w-100 btn-alt-primary">
                                                        <i class="fa fa-fw fa-sign-in-alt me-1 opacity-50"></i> Iniciar sesión
                                                    </button>
                                                </div>
                                            </div>
                                        </form>

                                        <!-- Incluir el script de reCAPTCHA -->
                                        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="fs-sm text-muted text-center">
                        <strong>Universidad Colegio Mayor de Cundinamarca</strong> &copy; <span data-toggle="year-copy"></span>
                        <div class="logos">                  
                            <img class="logo-otic" src="{{ asset('assets/media/favicons/logo_otic.png') }}" 
                            alt="Universidad Colegio Mayor de Cundinamarca">
                        </div>    
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="{{ asset('assets/js/oneui.app.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/jquery.min.js') }}"></script>
</body>
</html>
