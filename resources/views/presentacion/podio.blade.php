<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Podio de Resultados</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-600 to-indigo-700 min-h-screen flex items-center justify-center p-6 text-white">

    <div class="bg-white/10 backdrop-blur-lg rounded-3xl shadow-2xl p-8 w-full max-w-2xl text-center border border-white/20">
        
        <h2 class="text-2xl font-bold text-indigo-100 mb-2">Resultados para:</h2>
        <h1 class="text-4xl font-extrabold mb-8">{{ $pregunta->texto }}</h1>
        
        <p class="text-lg text-indigo-100 mb-10">¡Gracias por participar! Aquí están los 3 más votados.</p>

        <!-- Contenedor del Podio -->
        <div class="flex items-end justify-center gap-4 h-64">
            
            <!-- Segundo Lugar -->
            @if(isset($resultados[1]))
            <div class="flex flex-col items-center w-1/3">
                <div class="text-4xl font-bold text-gray-200">2</div>
                <div class="bg-gray-300 text-gray-800 font-bold p-4 rounded-t-lg w-full text-center shadow-lg">
                    <span class="text-xl">{{ $resultados[1]->respuesta }}</span>
                    <span class="block text-2xl font-bold">{{ $resultados[1]->total }}</span>
                </div>
                <div class="bg-gray-400 h-32 w-full rounded-b-lg shadow-inner"></div>
            </div>
            @endif

            <!-- Primer Lugar -->
            @if(isset($resultados[0]))
            <div class="flex flex-col items-center w-1/3">
                <div class="text-5xl font-bold text-yellow-300">1</div>
                <div class="bg-yellow-400 text-yellow-900 font-bold p-4 rounded-t-lg w-full text-center shadow-lg">
                    <span class="text-2xl">{{ $resultados[0]->respuesta }}</span>
                    <span class="block text-3xl font-bold">{{ $resultados[0]->total }}</span>
                </div>
                <div class="bg-yellow-500 h-48 w-full rounded-b-lg shadow-inner"></div>
            </div>
            @endif

            <!-- Tercer Lugar -->
            @if(isset($resultados[2]))
            <div class="flex flex-col items-center w-1/3">
                <div class="text-3xl font-bold text-orange-200">3</div>
                <div class="bg-orange-300 text-orange-800 font-bold p-4 rounded-t-lg w-full text-center shadow-lg">
                    <span class="text-lg">{{ $resultados[2]->respuesta }}</span>
                    <span class="block text-xl font-bold">{{ $resultados[2]->total }}</span>
                </div>
                <div class="bg-orange-400 h-24 w-full rounded-b-lg shadow-inner"></div>
            </div>
            @endif
            
            @if($resultados->isEmpty())
                <p class="text-xl text-indigo-100">Aún no hay votos para esta pregunta.</p>
            @endif
        </div>

        <div class="mt-12">
            <a href="{{ route('resultados', ['preguntaId' => $pregunta->id]) }}" 
               class="bg-white text-indigo-700 font-bold px-8 py-3 rounded-xl shadow-lg hover:bg-indigo-100 transition-colors duration-300">
                Ver todos los resultados
            </a>
        </div>
    </div>

</body>
</html>

