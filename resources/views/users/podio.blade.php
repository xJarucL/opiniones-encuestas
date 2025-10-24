<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Podio de Nominados</title>
    @vite(['resources/css/podio.css', 'resources/js/podio.js'])
</head>
<body>
    <div class="container">
        <!-- Luces de las esquinas -->
        <div class="spotlight left"></div>
        <div class="spotlight right"></div>

        <!-- Base del podio -->
        <div class="podium-base"></div>

        <!-- Tercer lugar -->
        <div class="podium-pillar pillar-3">
            <div class="pillar-number">3</div>
            <div class="pillar-name">
                @if($podio['tercero'])
                    {{ $podio['tercero']->nombre }}
                @else
                    ---
                @endif
            </div>
        </div>

        <!-- Primer lugar -->
        <div class="podium-pillar pillar-1">
            <div class="pillar-number">1</div>
            <div class="pillar-name">
                @if($podio['primero'])
                    {{ $podio['primero']->nombre }}
                @else
                    ---
                @endif
            </div>
            <!-- Estrella -->
            <div class="star">
                <svg viewBox="0 0 100 100" width="80" height="80">
                    <polygon points="50,15 61,40 88,40 67,57 73,82 50,67 27,82 33,57 12,40 39,40" 
                             fill="#FFD700" stroke="#000" stroke-width="2"/>
                </svg>
            </div>
        </div>

        <!-- Segundo lugar -->
        <div class="podium-pillar pillar-2">
            <div class="pillar-number">2</div>
            <div class="pillar-name">
                @if($podio['segundo'])
                    {{ $podio['segundo']->nombre }}
                @else
                    ---
                @endif
            </div>
        </div>
    </div>
</body>
</html>