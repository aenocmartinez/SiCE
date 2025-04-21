<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Prueba de escudo</title>
    <style>
        body {
            text-align: center;
            font-family: sans-serif;
        }

        .logo {
            margin-top: 100px;
        }

        .logo img {
            height: 100px;
        }
    </style>
</head>
<body>

    <h1>Prueba del Escudo</h1>

    <div class="logo">
        <img src="file://{{ public_path('certificados/images/escudo.png') }}" alt="Escudo">
    </div>

</body>
</html>
