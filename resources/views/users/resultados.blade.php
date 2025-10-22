<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados - Mejor Programador</title>
    @vite(['resources/css/resultados.css', 'resources/js/resultados.js'])
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>El mejor en programación</h1>
            <p class="participants">12 Participantes</p>
        </div>

        <div class="results-list">
            <!-- Primer lugar -->
            <div class="result-row" data-position="1">
                <div class="medal">
                    <img src="{{ asset('img/trofeo.png') }}" alt="Trofeo">
                </div>
                <div class="bar-container">
                    <div class="progress-bar" data-percentage="100">
                        <span class="name">Jaruny</span>
                    </div>
                </div>
                <div class="percentage">100%</div>
            </div>

            <!-- Segundo lugar -->
            <div class="result-row" data-position="2">
                <div class="medal">
                    <img src="{{ asset('img/medalla-plata.png') }}" alt="Medalla plata">
                </div>
                <div class="bar-container">
                    <div class="progress-bar" data-percentage="90">
                        <span class="name">Maria</span>
                    </div>
                </div>
                <div class="percentage">90%</div>
            </div>

            <!-- Tercer lugar -->
            <div class="result-row" data-position="3">
                <div class="medal">
                    <img src="{{ asset('img/medalla-bronce.png') }}" alt="Medalla bronce">
                </div>
                <div class="bar-container">
                    <div class="progress-bar" data-percentage="85">
                        <span class="name">Edi</span>
                    </div>
                </div>
                <div class="percentage">85%</div>
            </div>

            <!-- Cuarto lugar -->
            <div class="result-row" data-position="4">
                <div class="medal">
                    <span class="position-number">4</span>
                </div>
                <div class="bar-container">
                    <div class="progress-bar" data-percentage="60">
                        <span class="name">José</span>
                    </div>
                </div>
                <div class="percentage">60%</div>
            </div>

            <!-- Quinto lugar -->
            <div class="result-row" data-position="5">
                <div class="medal">
                    <span class="position-number">5</span>
                </div>
                <div class="bar-container">
                    <div class="progress-bar" data-percentage="45">
                        <span class="name">Pablito</span>
                    </div>
                </div>
                <div class="percentage">45%</div>
            </div>
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