@extends('components.menu')

@section('title', 'Responder Encuestas')

@section('content')
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .card-animate {
        animation: fadeIn 0.6s ease-out forwards;
        opacity: 0;
    }
    .card-animate:nth-child(1) { animation-delay: 0.1s; }
    .card-animate:nth-child(2) { animation-delay: 0.2s; }
    .card-animate:nth-child(3) { animation-delay: 0.3s; }
    .card-animate:nth-child(4) { animation-delay: 0.4s; }
    .card-animate:nth-child(5) { animation-delay: 0.5s; }
    .header-animate { animation: slideIn 0.8s ease-out; }
</style>

<div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-indigo-50 py-8 px-4">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header con Botón de Regreso -->
        <header class="header-animate mb-10">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-3 rounded-2xl shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        <h1 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-600">
                            Encuestas Disponibles
                        </h1>
                    </div>
                    <p class="text-lg md:text-xl text-gray-600 ml-16">Participa y comparte tu opinión. Tu voz es importante. 💬</p>
                </div>
                
                <!-- Botón Regresar al Inicio -->
                <a href="{{ url('/') }}" 
                   class="group flex items-center gap-2 bg-white hover:bg-gradient-to-r hover:from-purple-600 hover:to-indigo-600 text-purple-600 hover:text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border-2 border-purple-200 hover:border-transparent">
                    <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="hidden md:inline">Inicio</span>
                </a>
            </div>
            
            <!-- Estadísticas -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-5 rounded-2xl shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium mb-1">Total Encuestas</p>
                            <p class="text-3xl font-black">{{ $encuestas->total() }}</p>
                        </div>
                        <div class="bg-white/20 p-3 rounded-xl">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>
                
                
                <div class="bg-gradient-to-br from-pink-500 to-pink-600 text-white p-5 rounded-2xl shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-pink-100 text-sm font-medium mb-1">Participantes</p>
                            <p class="text-3xl font-black">{{ $encuestas->sum(fn($e) => $e->preguntas->count()) }}</p>
                        </div>
                        <div class="bg-white/20 p-3 rounded-xl">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Lista de Encuestas -->
        <div class="space-y-6">
            @forelse($encuestas as $encuesta)
                <div class="card-animate group bg-white rounded-3xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 border border-gray-100 hover:border-purple-200">
                    <div class="p-6 md:p-8 relative">
                        
                        <!-- Decoración de fondo -->
                        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-purple-100/50 to-indigo-100/50 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 -mr-32 -mt-32"></div>
                        
                        <div class="relative z-10">
                            <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-6">
                                <!-- Info de la Encuesta -->
                                <div class="flex-1">
                                    <div class="flex items-start gap-4 mb-4">
                                        <div class="flex-shrink-0 bg-gradient-to-br from-purple-500 to-indigo-600 p-4 rounded-2xl shadow-lg transform group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2 group-hover:text-purple-600 transition-colors duration-300">
                                                {{ $encuesta->titulo }}
                                            </h2>
                                            <p class="text-gray-600 text-base md:text-lg leading-relaxed">
                                                {{ $encuesta->descripcion }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Botón de Acción -->
                                <div class="flex-shrink-0">
                                    @if ($encuesta->preguntas->isNotEmpty())
                                        <a href="{{ route('presentacion', ['preguntaId' => $encuesta->preguntas->first()->id]) }}" 
                                            class="group/btn relative inline-flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold px-8 py-4 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden hover:scale-105 active:scale-95">
                                            <div class="absolute inset-0 bg-gradient-to-r from-purple-700 to-indigo-700 opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></div>
                                            <span class="relative">Responder</span>
                                            <svg class="relative w-5 h-5 transform group-hover/btn:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                            </svg>
                                        </a>
                                    @else
                                        <span class="inline-flex items-center gap-2 bg-gray-100 text-gray-500 font-semibold px-8 py-4 rounded-2xl cursor-not-allowed border-2 border-gray-200">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Sin preguntas
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Metadata -->
                            <div class="flex flex-wrap items-center gap-6 mt-6 pt-6 border-t border-gray-100">
                                <div class="flex items-center gap-2 text-gray-600">
                                    <div class="bg-purple-100 p-2 rounded-lg">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <span class="font-semibold">{{ $encuesta->preguntas->count() }}</span>
                                    <span class="text-sm">{{ Str::plural('Pregunta', $encuesta->preguntas->count()) }}</span>
                                </div>
                                
                                <div class="flex items-center gap-2 text-gray-600">
                                    <div class="bg-indigo-100 p-2 rounded-lg">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm">Publicada: <span class="font-semibold">{{ $encuesta->created_at->format('d/m/Y') }}</span></span>
                                </div>
                                
                                <div class="flex items-center gap-2 text-gray-600">
                                    <div class="bg-green-100 p-2 rounded-lg">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-semibold text-green-600">Activa</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Mensaje si no hay encuestas -->
                <div class="bg-white rounded-3xl shadow-xl p-12 md:p-16 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="bg-gradient-to-br from-purple-100 to-indigo-100 p-8 rounded-full mb-6 shadow-inner">
                            <svg class="w-16 h-16 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        <p class="text-gray-800 text-2xl font-bold mb-3">¡Todo listo por aquí! ✨</p>
                        <p class="text-gray-500 text-lg max-w-md">No hay encuestas activas para responder en este momento. Vuelve pronto para participar.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if($encuestas->hasPages())
        <div class="mt-10">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                {{ $encuestas->links() }}
            </div>
        </div>
        @endif

    </div>
</div>
@endsection