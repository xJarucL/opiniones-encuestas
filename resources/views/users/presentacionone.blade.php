<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presentación - Nominados</title>
    @vite(['resources/css/presentacion.css', 'resources/js/presentacionone.js'])
</head>
<body>
    <div class="container">
        <div class="envelope-wrapper" id="envelopeWrapper">
            <img src="{{ asset('img/Sello1.png') }}" alt="Sello con listón" class="seal-ribbon">
            <div class="lid one"></div>
            <div class="lid two"></div>
            <div class="envelope-base"></div>
            <div class="letter"></div>
        </div>

        <div class="nominees-text" id="nomineesText">
            <div class="nominees-card">
                <h1>Los nominados a<br>{{ $tituloEncuesta }}<br>son .....</h1>
            </div>
        </div>

        <div class="click-hint">Haz clic en el sobre</div>
    </div>
</body>
</html>