<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados - {{ $tituloEncuesta }}</title>
    @vite(['resources/css/resultados.css', 'resources/js/resultados.js'])
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $tituloEncuesta }}</h1>
            <p class="participants">{{ $totalParticipantes }} Participantes</p>

            <!-- Botón cerrar (X) -->
            <a href="{{ route('inicio') }}" class="close-button">
                <img src="{{ asset('img/close-icon.png') }}" alt="Cerrar">
            </a>
        </div>

        <div class="results-list">
            @foreach($resultados as $index => $resultado)
                <div class="result-row" data-position="{{ $index + 1 }}">
                    <div class="medal">
                        @if($index === 0)
                            <!-- Primer lugar - Trofeo -->
                            <img src="{{ asset('img/trofeo.png') }}" alt="Trofeo">
                        @elseif($index === 1)
                            <!-- Segundo lugar - Medalla plata -->
                            <img src="{{ asset('img/medalla-plata.png') }}" alt="Medalla plata">
                        @elseif($index === 2)
                            <!-- Tercer lugar - Medalla bronce -->
                            <img src="{{ asset('img/medalla-bronce.png') }}" alt="Medalla bronce">
                        @else
                            <!-- Del 4to en adelante - Número -->
                            <span class="position-number">{{ $index + 1 }}</span>
                        @endif
                    </div>
                    
                    <div class="bar-container">
                        <div class="progress-bar" data-percentage="{{ $resultado->porcentaje }}">
                            <span class="name">{{ $resultado->nombre }}</span>
                        </div>
                    </div>
                    
                    <div class="percentage">{{ $resultado->porcentaje }}%</div>
                </div>
            @endforeach
        </div>

        <!-- Botón repetir -->
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