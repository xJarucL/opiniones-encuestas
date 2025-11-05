    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Podio - {{ $pregunta->texto }}</title>
        @vite(['resources/css/podiotwo.css', 'resources/js/podiotwo.js'])
        <script>
            const encuestaId = {{ $encuestaId }};
            const preguntaIndex = {{ $preguntaIndex }};
            const hayMasPreguntas = {{ $hayMasPreguntas ? 'true' : 'false' }};
        </script>
    </head>
    <body>
        <div class="container">
            <!-- Luces -->
            <div class="spotlight left"></div>
            <div class="spotlight right"></div>

            <!-- Base del podio -->
            <div class="podium-base"></div>

            <!-- Pilar 3 -->
            @if(isset($topRespuestas[2]))
            <div class="podium-pillar pillar-3">
                <div class="pillar-name">{{ $topRespuestas[2]->opcion }}</div>
                <div class="pillar-number">3</div>
                <div class="pillar-percent">{{ $topRespuestas[2]->porcentaje }}%</div>
            </div>
            @endif

            <!-- Pilar 1 -->
            @if(isset($topRespuestas[0]))
            <div class="podium-pillar pillar-1">
                <div class="pillar-name">{{ $topRespuestas[0]->opcion }}</div>
                <div class="pillar-number">1</div>
                <div class="pillar-percent">{{ $topRespuestas[0]->porcentaje }}%</div>
                <div class="star">
                    <svg viewBox="0 0 100 100" width="80" height="80">
                        <polygon points="50,15 61,40 88,40 67,57 73,82 50,67 27,82 33,57 12,40 39,40" 
                                fill="#FFD700" stroke="#000" stroke-width="2"/>
                    </svg>
                </div>
            </div>
            @endif

            <!-- Pilar 2 -->
            @if(isset($topRespuestas[1]))
            <div class="podium-pillar pillar-2">
                <div class="pillar-name">{{ $topRespuestas[1]->opcion }}</div>
                <div class="pillar-number">2</div>
                <div class="pillar-percent">{{ $topRespuestas[1]->porcentaje }}%</div>
            </div>
            @endif

            <!-- Botón siguiente -->
            <button class="next-button">
                Siguiente
                <svg class="next-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    viewBox="0 0 24 24">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </button>
        </div>
    </body>
    </html>
