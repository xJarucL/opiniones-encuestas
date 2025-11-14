<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico de Respuestas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">🔍 Diagnóstico de Encuesta</h1>
        
        <!-- Información de la Encuesta -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4 text-blue-600">📋 Información de la Encuesta</h2>
            @if($encuesta)
                <div class="grid grid-cols-2 gap-4">
                    <div><strong>ID:</strong> {{ $encuesta->id }}</div>
                    <div><strong>Título:</strong> {{ $encuesta->titulo }}</div>
                    <div><strong>Estado:</strong> {{ $encuesta->estado ? 'Activa' : 'Inactiva' }}</div>
                    <div><strong>Categoría ID:</strong> {{ $encuesta->categoria_id }}</div>
                </div>
            @else
                <p class="text-red-600">❌ Encuesta no encontrada</p>
            @endif
        </div>

        <!-- Estructura de la tabla respuestas -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4 text-purple-600">🗄️ Estructura de la Tabla Respuestas</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full border">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border px-4 py-2">Campo</th>
                            <th class="border px-4 py-2">Tipo</th>
                            <th class="border px-4 py-2">Nulo</th>
                            <th class="border px-4 py-2">Key</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($columnasRespuestas as $columna)
                            <tr class="{{ $columna->Field == 'texto' ? 'bg-yellow-100' : '' }}">
                                <td class="border px-4 py-2 font-mono">{{ $columna->Field }}</td>
                                <td class="border px-4 py-2">{{ $columna->Type }}</td>
                                <td class="border px-4 py-2">{{ $columna->Null }}</td>
                                <td class="border px-4 py-2">{{ $columna->Key }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Muestra de Datos Crudos -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4 text-orange-600">📊 Muestra de Respuestas (Primeras 10)</h2>
            @if($muestraRespuestas->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full border text-sm">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="border px-2 py-1">ID</th>
                                <th class="border px-2 py-1">Pregunta ID</th>
                                <th class="border px-2 py-1">User ID (Votante)</th>
                                <th class="border px-2 py-1">TEXTO (Nominado)</th>
                                <th class="border px-2 py-1">Respuesta</th>
                                <th class="border px-2 py-1">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($muestraRespuestas as $respuesta)
                                <tr class="{{ empty($respuesta->texto) ? 'bg-red-100' : 'bg-green-50' }}">
                                    <td class="border px-2 py-1">{{ $respuesta->id }}</td>
                                    <td class="border px-2 py-1">{{ $respuesta->pregunta_id }}</td>
                                    <td class="border px-2 py-1">{{ $respuesta->user_id }}</td>
                                    <td class="border px-2 py-1 font-bold">
                                        @if(empty($respuesta->texto))
                                            <span class="text-red-600">❌ VACÍO</span>
                                        @else
                                            <span class="text-green-600">✓ {{ $respuesta->texto }}</span>
                                        @endif
                                    </td>
                                    <td class="border px-2 py-1 text-xs">{{ $respuesta->respuesta ?? 'N/A' }}</td>
                                    <td class="border px-2 py-1">{{ $respuesta->valor ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600">No hay respuestas registradas</p>
            @endif
        </div>

        <!-- Análisis por Pregunta -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4 text-green-600">📈 Análisis por Pregunta</h2>
            
            @foreach($analisisPorPregunta as $analisis)
                <div class="mb-6 border-l-4 border-green-500 pl-4">
                    <h3 class="text-xl font-semibold mb-2">{{ $analisis['pregunta_texto'] }}</h3>
                    
                    <div class="grid grid-cols-3 gap-4 mb-4 bg-gray-50 p-4 rounded">
                        <div>
                            <strong>Total respuestas:</strong> 
                            <span class="text-2xl font-bold text-blue-600">{{ $analisis['total_respuestas'] }}</span>
                        </div>
                        <div>
                            <strong>Respuestas vacías:</strong> 
                            <span class="text-2xl font-bold {{ $analisis['respuestas_vacias'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                                {{ $analisis['respuestas_vacias'] }}
                            </span>
                        </div>
                        <div>
                            <strong>Pregunta ID:</strong> 
                            <span class="text-lg font-mono">{{ $analisis['pregunta_id'] }}</span>
                        </div>
                    </div>
                    
                    <h4 class="font-semibold mb-2">🏆 Top 3 Nominados:</h4>
                    @if($analisis['top_3']->count() > 0)
                        <div class="space-y-2">
                            @foreach($analisis['top_3'] as $index => $resultado)
                                <div class="flex items-center gap-4 bg-gray-50 p-3 rounded">
                                    <span class="text-2xl">{{ $index == 0 ? '🥇' : ($index == 1 ? '🥈' : '🥉') }}</span>
                                    <span class="flex-1 font-semibold {{ $resultado['es_vacio'] ? 'text-red-600' : 'text-gray-800' }}">
                                        {{ $resultado['nombre_nominado'] }}
                                    </span>
                                    <span class="bg-blue-100 px-4 py-1 rounded-full font-bold">
                                        {{ $resultado['total_votos'] }} votos
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No hay votos para esta pregunta</p>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Botón de Retorno -->
        <div class="text-center">
            <a href="{{ url()->previous() }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 inline-block">
                ← Volver
            </a>
        </div>
    </div>
</body>
</html>