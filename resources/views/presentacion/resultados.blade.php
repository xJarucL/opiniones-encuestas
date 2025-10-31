<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados Completos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">

    <div class="max-w-3xl mx-auto bg-white rounded-3xl shadow-xl overflow-hidden">
        
        <div class="bg-gradient-to-r from-purple-600 to-indigo-700 p-8 text-white">
            <h2 class="text-xl font-bold text-indigo-100 mb-1">Resultados Completos</h2>
            <h1 class="text-3xl font-extrabold mb-4">{{ $pregunta->texto }}</h1>
            <p class="text-indigo-100">Total de votos: <span class="font-bold text-2xl">{{ $totalVotos }}</span></p>
        </div>
        
        <div class="p-8">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Desglose de votos:</h3>
            
            <div class="space-y-4">
                @forelse($resultados as $resultado)
                    @php
                        // Calcular porcentaje
                        $porcentaje = ($totalVotos > 0) ? ($resultado->total / $totalVotos) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-bold text-gray-800 text-lg">{{ $resultado->respuesta }}</span>
                            <span class="text-gray-600 font-semibold">{{ $resultado->total }} Votos ({{ number_format($porcentaje, 1) }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="bg-purple-600 h-4 rounded-full" 
                                 style="width: {{ $porcentaje }}%">
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">No se han recibido votos para esta pregunta todavía.</p>
                @endforelse
            </div>
        </div>
        
        <div class="p-6 bg-gray-50 border-t border-gray-200 text-center">
             <a href="{{ route('encuestas.index') }}" 
               class="text-purple-700 font-semibold hover:text-purple-900 transition-colors">
                &larr; Volver a la lista de encuestas
            </a>
        </div>
    </div>

</body>
</html>
