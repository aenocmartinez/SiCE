<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <style>
        .active a {
            color: red;
            text-decoration: none;
        }
    </style>
</head>
<body>

    @include('parciales.nav')
    @yield('content')
    
</body>
</html>