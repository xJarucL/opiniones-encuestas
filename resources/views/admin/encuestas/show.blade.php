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

{{-- Alerta flotante global --}}
<div class="mb-6 animate-fade-slide relative z-50" style="animation-delay: 0.1s;">
    <x-msj-alert />
</div>

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

    </div>
</div>

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

<!-- Botón flotante -->
<a href="{{ route('validarButton', $encuesta->id) }}" 
   class="boton-flotante">
   Ver Resultados
</a>

<style>
.boton-flotante {
    position: fixed;
    bottom: 20px;
    right: 12px;
    display: inline-block;
    background-color: #16a34a;
    color: white;
    padding: 8px 10px;
    border-radius: 8px;
    font-size: 0.95rem;
    font-weight: 600;
    text-decoration: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
    transition: background-color 0.3s, transform 0.2s;
    z-index: 1000;
}
.boton-flotante:hover {
    background-color: #15803d;
    transform: scale(1.05);
}
</style>

@endsection

@section('styles')
<style>
    #mensaje {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%) translateY(-100%);
        z-index: 9999;
        min-width: 250px;
        max-width: 90%;
        text-align: center;
        padding: 12px 20px;
        border-radius: 8px;
        color: white;
        font-size: 0.875rem;
        font-weight: 500;
        box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        opacity: 0;
        animation: slideDown 0.5s forwards;
    }

    .success {
        background-color: #16a34a; /* green-600 */
    }
    .error {
        background-color: #dc2626; /* red-600 */
    }

    @keyframes slideDown {
        from {
            transform: translateX(-50%) translateY(-100%);
            opacity: 0;
        }
        to {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }
    }

    @keyframes slideUp {
        from {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }
        to {
            transform: translateX(-50%) translateY(-100%);
            opacity: 0;
        }
    }
</style>
@endsection

@section('scripts')
@if (session('success') || session('error'))
    <div id="mensaje" class="{{ session('success') ? 'success' : 'error' }}">
        {{ session('success') ?? session('error') }}
    </div>
@else
    <div id="mensaje" class="hidden"></div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    const mensaje = document.getElementById('mensaje');
    
    if (mensaje && !mensaje.classList.contains('hidden') && (mensaje.textContent.trim().length > 0)) {
        setTimeout(() => {
            mensaje.style.animation = 'slideUp 0.5s forwards';
            setTimeout(() => {
                mensaje.style.display = 'none';
                mensaje.classList.add('hidden');
            }, 500);
        }, 4000);
    } else {
        if (mensaje) mensaje.style.display = 'none';
    }
});
</script>
@endsection