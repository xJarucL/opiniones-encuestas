<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Podio de Resultados</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .podio-animation {
            animation: slideUp 0.8s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-purple-600 to-indigo-700 min-h-screen flex items-center justify-center p-6 text-white">

    <div class="bg-white/10 backdrop-blur-lg rounded-3xl shadow-2xl p-8 w-full max-w-2xl text-center border border-white/20">
        
        <h2 class="text-2xl font-bold text-indigo-100 mb-2">Resultados para:</h2>
        <h1 class="text-4xl font-extrabold mb-8">{{ $pregunta->texto }}</h1>
        
        <p class="text-lg text-indigo-100 mb-10">¡Gracias por participar! Aquí están los 3 más votados.</p>

        <!-- Contenedor del Podio -->
        @if(count($resultados) > 0)
            <div class="flex items-end justify-center gap-4 h-64 mb-8">
                
                <!-- Segundo Lugar -->
                @if(isset($resultados[1]) && $resultados[1]->respuesta)
                <div class="flex flex-col items-center w-1/3 podio-animation" style="animation-delay: 0.2s;">
                    <div class="text-4xl font-bold text-gray-200 mb-2">🥈</div>
                    <div class="bg-gray-300 text-gray-800 font-bold p-4 rounded-t-lg w-full text-center shadow-lg">
                        <span class="text-lg block mb-1 truncate px-2" title="{{ $resultados[1]->respuesta }}">
                            {{ $resultados[1]->respuesta }}
                        </span>
                        <span class="block text-2xl font-bold">{{ $resultados[1]->total }} votos</span>
                        <span class="text-sm text-gray-600">{{ $resultados[1]->porcentaje }}%</span>
                    </div>
                    <div class="bg-gray-400 h-32 w-full rounded-b-lg shadow-inner"></div>
                </div>
                @endif

                <!-- Primer Lugar -->
                @if(isset($resultados[0]) && $resultados[0]->respuesta)
                <div class="flex flex-col items-center w-1/3 podio-animation" style="animation-delay: 0s;">
                    <div class="text-5xl font-bold text-yellow-300 mb-2">🏆</div>
                    <div class="bg-yellow-400 text-yellow-900 font-bold p-4 rounded-t-lg w-full text-center shadow-lg">
                        <span class="text-xl block mb-1 truncate px-2" title="{{ $resultados[0]->respuesta }}">
                            {{ $resultados[0]->respuesta }}
                        </span>
                        <span class="block text-3xl font-bold">{{ $resultados[0]->total }} votos</span>
                        <span class="text-sm text-yellow-700">{{ $resultados[0]->porcentaje }}%</span>
                    </div>
                    <div class="bg-yellow-500 h-48 w-full rounded-b-lg shadow-inner"></div>
                </div>
                @endif

                <!-- Tercer Lugar -->
                @if(isset($resultados[2]) && $resultados[2]->respuesta)
                <div class="flex flex-col items-center w-1/3 podio-animation" style="animation-delay: 0.4s;">
                    <div class="text-3xl font-bold text-orange-200 mb-2">🥉</div>
                    <div class="bg-orange-300 text-orange-800 font-bold p-4 rounded-t-lg w-full text-center shadow-lg">
                        <span class="text-base block mb-1 truncate px-2" title="{{ $resultados[2]->respuesta }}">
                            {{ $resultados[2]->respuesta }}
                        </span>
                        <span class="block text-xl font-bold">{{ $resultados[2]->total }} votos</span>
                        <span class="text-sm text-orange-600">{{ $resultados[2]->porcentaje }}%</span>
                    </div>
                    <div class="bg-orange-400 h-24 w-full rounded-b-lg shadow-inner"></div>
                </div>
                @endif
            </div>
        @else
            <p class="text-xl text-indigo-100 my-12">Aún no hay votos para esta pregunta.</p>
        @endif

        <div class="mt-8 flex gap-4 justify-center">
            @if($hayMasPreguntas)
                <a href="{{ route('presentaciontwo.inicio', ['encuestaId' => $encuestaId, 'preguntaIndex' => $preguntaIndex + 1]) }}" 
                   class="bg-white text-indigo-700 font-bold px-8 py-3 rounded-xl shadow-lg hover:bg-indigo-100 transition-colors duration-300">
                    Siguiente Pregunta →
                </a>
            @else
                <a href="{{ route('resultadostwo', $encuestaId) }}" 
                   class="bg-white text-indigo-700 font-bold px-8 py-3 rounded-xl shadow-lg hover:bg-indigo-100 transition-colors duration-300">
                    Ver Todos los Resultados
                </a>
            @endif
        </div>
    </div>

</body>
</html>