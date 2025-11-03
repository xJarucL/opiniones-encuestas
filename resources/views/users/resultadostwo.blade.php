<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados - {{ $tituloEncuesta }}</title>
    @vite(['resources/css/resultadostwo.css', 'resources/js/resultadostwo.js'])
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $tituloEncuesta }}</h1>

            <!-- Botón cerrar (X) -->
            <a href="{{ route('inicio') }}" class="close-button">
                <img src="{{ asset('img/close-icon.png') }}" alt="Cerrar">
            </a>
        </div>

        @foreach($resultados as $preguntaId => $respuestas)
            <div class="pregunta-block">
                <h2 class="pregunta-titulo">{{ $respuestas[0]->pregunta }}</h2>

                <div class="respuestas-list">
                    @foreach($respuestas as $index => $r)
                        <div class="respuesta-row {{ $index >= 3 ? 'extra hidden' : '' }}">
                            <div class="medal">
                                @if($index === 0)
                                    <img src="{{ asset('img/trofeo.png') }}" alt="Trofeo">
                                @elseif($index === 1)
                                    <img src="{{ asset('img/medalla-plata.png') }}" alt="Medalla de plata">
                                @elseif($index === 2)
                                    <img src="{{ asset('img/medalla-bronce.png') }}" alt="Medalla de bronce">
                                @else
                                    <span class="position-number">{{ $index + 1 }}</span>
                                @endif
                            </div>

                            <div class="respuesta-nombre">{{ $r->opcion ?? 'Sin respuesta' }}</div>

                            <div class="bar-container">
                                <div class="progress-bar" style="width: {{ $r->porcentaje }}%">
                                    {{ $r->porcentaje }}%
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if(count($respuestas) > 3)
                    <button class="toggle-btn" data-target="pregunta-{{ $preguntaId }}">
                        Ver todas las respuestas
                    </button>
                @endif

                <hr class="pregunta-divider">
            </div>
        @endforeach

        <!-- Botón repetir animación -->
        <button class="repeat-button">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M1 4v6h6M23 20v-6h-6"/>
                <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"/>
            </svg>
            Repetir Animación
        </button>
    </div>
</body>
</html>
