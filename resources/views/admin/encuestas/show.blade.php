@extends('admin.admin-layout')

@section('title', 'Resultados - ' . $encuesta->titulo)

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.encuestas.index') }}" class="text-green-600 hover:text-green-700 mb-2 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Volver a Encuestas
    </a>
    <h1 class="text-3xl font-bold text-gray-800 mt-2">Resultados: {{ $encuesta->titulo }}</h1>
    <p class="text-gray-600 mt-1">Análisis de respuestas y estadísticas</p>
</div>

<!-- Información de la Encuesta -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-sm font-semibold text-gray-500 mb-1">Descripción</h3>
            <p class="text-gray-800">{{ $encuesta->descripcion ?: 'Sin descripción' }}</p>
        </div>
        <div>
            <h3 class="text-sm font-semibold text-gray-500 mb-1">Categoría</h3>
            <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm">
                {{ $encuesta->categoria->nombre ?? 'Sin categoría' }}
            </span>
        </div>
        <div>
            <h3 class="text-sm font-semibold text-gray-500 mb-1">Estado</h3>
            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $encuesta->estado ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                {{ $encuesta->estado ? 'Activa' : 'Inactiva' }}
            </span>
        </div>
        <div>
            <h3 class="text-sm font-semibold text-gray-500 mb-1">Creada</h3>
            <p class="text-gray-800">{{ $encuesta->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</div>

<!-- Métricas principales -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-blue-50 p-6 rounded-xl">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-blue-600 font-semibold mb-1">Total Respuestas</p>
                <p class="text-3xl font-bold text-blue-700">{{ $totalRespuestas }}</p>
            </div>
            <svg class="w-10 h-10 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
        </div>
    </div>
    <div class="bg-green-50 p-6 rounded-xl">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-green-600 font-semibold mb-1">Preguntas</p>
                <p class="text-3xl font-bold text-green-700">{{ $encuesta->preguntas->count() }}</p>
            </div>
            <svg class="w-10 h-10 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </div>
    <div class="bg-purple-50 p-6 rounded-xl">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-purple-600 font-semibold mb-1">Promedio</p>
                <p class="text-3xl font-bold text-purple-700">{{ number_format($promedioRespuestas, 1) }}</p>
            </div>
            <svg class="w-10 h-10 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
        </div>
    </div>
    <div class="bg-orange-50 p-6 rounded-xl">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-orange-600 font-semibold mb-1">Última Respuesta</p>
                <p class="text-lg font-bold text-orange-700">{{ $ultimaRespuesta ?? 'N/A' }}</p>
            </div>
            <svg class="w-10 h-10 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </div>
</div>

<!-- Resultados por pregunta -->
@foreach($encuesta->preguntas as $pregunta)
<div class="bg-white rounded-xl shadow-lg p-6 mb-6">
    <h3 class="text-xl font-bold text-gray-800 mb-4">{{ $pregunta->texto }}</h3>
    <p class="text-sm text-gray-500 mb-4">Tipo: 
        @if($pregunta->tipo == 'multiple')
            Opción múltiple
        @elseif($pregunta->tipo == 'text')
            Texto libre
        @else
            Calificación
        @endif
    </p>

    @if($pregunta->tipo == 'multiple' || $pregunta->tipo == 'rating')
        <!-- Gráfico de barras -->
        <div class="space-y-3">
            @php
                $respuestas = $pregunta->respuestas->groupBy('respuesta')->map->count();
                $total = $pregunta->respuestas->count();
            @endphp
            
            @if($total > 0)
                @foreach($respuestas as $opcion => $cantidad)
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700">{{ $opcion }}</span>
                        <span class="text-sm font-semibold text-gray-800">
                            {{ $cantidad }} ({{ number_format(($cantidad / $total) * 100, 1) }}%)
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 h-full rounded-full transition-all duration-500"
                             style="width: {{ ($cantidad / $total) * 100 }}%">
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <p class="text-gray-500 text-center py-4">Sin respuestas aún</p>
            @endif
        </div>
    @else
        <!-- Respuestas de texto -->
        <div class="space-y-3 max-h-96 overflow-y-auto">
            @forelse($pregunta->respuestas as $respuesta)
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-800">{{ $respuesta->respuesta }}</p>
                <p class="text-xs text-gray-500 mt-2">
                    {{ $respuesta->created_at->diffForHumans() }}
                </p>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">Sin respuestas aún</p>
            @endforelse
        </div>
    @endif
</div>
@endforeach

@endsection 