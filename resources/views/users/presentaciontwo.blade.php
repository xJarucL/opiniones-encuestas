<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pregunta</title>
    @vite(['resources/css/presentaciontwo.css', 'resources/js/presentaciontwo.js'])
    <script>
        const encuestaId = {{ $encuestaId }};
        const preguntaIndex = {{ $preguntaIndex }};
    </script>
</head>
<body>
    <div class="container">
        <div class="box animate">
            <h1>{{ $tituloEncuesta }}</h1>
        </div>
    </div>
</body>
</html>
