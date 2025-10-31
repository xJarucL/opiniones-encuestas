@extends('components.menu')

@section('title', 'Respondiendo Encuesta')

@section('content')
<style>
    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    .card-animate {
        animation: fadeInScale 0.5s ease-out;
    }
    .option-animate {
        animation: slideInLeft 0.4s ease-out forwards;
        opacity: 0;
    }
    .option-animate:nth-child(1) { animation-delay: 0.1s; }
    .option-animate:nth-child(2) { animation-delay: 0.2s; }
    .option-animate:nth-child(3) { animation-delay: 0.3s; }
    .option-animate:nth-child(4) { animation-delay: 0.4s; }
    .option-animate:nth-child(5) { animation-delay: 0.5s; }
    .option-animate:nth-child(n+6) { animation-delay: 0.6s; }
</style>

<div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    
    <div class="card-animate bg-white rounded-3xl shadow-2xl w-full max-w-3xl overflow-hidden border border-gray-100">
        
        <!-- Header de la Encuesta con Gradiente -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-4 md:p-5 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <div class="relative z-10">
                <div class="inline-block bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-semibold mb-1">
                    📋 Encuesta
                </div>
                <h3 class="text-lg md:text-xl font-bold">{{ $pregunta->encuesta->titulo }}</h3>
            </div>
        </div>

        <!-- Cuerpo de la Pregunta -->
        <div class="p-4 md:p-6">
            
            <!-- Texto de la Pregunta -->
            <div class="text-center mb-5 md:mb-6">
                <div class="inline-block bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold mb-3">
                    Pregunta {{ $pregunta->orden ?? '' }}
                </div>
                <h1 class="text-xl md:text-2xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-600 leading-tight">
                    {{ $pregunta->texto }}
                </h1>
            </div>

            @if ($haVotado)
                {{-- Si ya votó, mostrar mensaje de agradecimiento --}}
                <div class="text-center space-y-4 py-4">
                    <div class="inline-block">
                        <svg class="w-16 h-16 text-green-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-gray-800">¡Gracias por tu voto! 🎉</h3>
                    <p class="text-gray-600 text-sm md:text-base max-w-md mx-auto">
                        Ya has respondido esta pregunta. Ahora puedes ver cómo votaron los demás.
                    </p>
                    
                    <div class="grid sm:grid-cols-2 gap-3 pt-2 max-w-lg mx-auto">
                        <a href="{{ route('podio', ['preguntaId' => $pregunta->id]) }}" 
                           class="group relative bg-gradient-to-r from-yellow-400 to-yellow-600 text-white font-bold px-4 py-3 rounded-xl shadow-lg hover:shadow-2xl hover:scale-105 transition-all duration-300 overflow-hidden text-sm">
                            <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                            <span class="relative flex items-center justify-center gap-2">
                                🏆 Ver Podio
                            </span>
                        </a>
                        <a href="{{ route('resultados', ['preguntaId' => $pregunta->id]) }}" 
                           class="group relative bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold px-4 py-3 rounded-xl shadow-lg hover:shadow-2xl hover:scale-105 transition-all duration-300 overflow-hidden text-sm">
                            <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                            <span class="relative flex items-center justify-center gap-2">
                                📊 Ver Resultados
                            </span>
                        </a>
                    </div>
                </div>

            @else
                {{-- Si NO ha votado, mostrar el formulario --}}
                <form action="{{ route('presentacion.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="pregunta_id" value="{{ $pregunta->id }}">
                    
                    @if(count($opciones) > 0)
                        <div class="space-y-2">
                            @foreach($opciones as $index => $opcion)
                                <div class="option-animate">
                                    <input type="radio" 
                                           name="respuesta" 
                                           value="{{ $opcion }}" 
                                           id="opcion-{{ Str::slug($opcion) }}" 
                                           class="hidden peer" 
                                           required>
                                    <label for="opcion-{{ Str::slug($opcion) }}"
                                           class="group block w-full text-left p-3 md:p-4 bg-gradient-to-r from-white to-gray-50 border-2 border-gray-200 rounded-xl
                                                  text-sm md:text-base font-semibold text-gray-700 cursor-pointer
                                                  peer-checked:border-purple-500 peer-checked:from-purple-50 peer-checked:to-purple-100 peer-checked:text-purple-700 peer-checked:shadow-lg
                                                  hover:border-purple-400 hover:shadow-md
                                                  transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98]
                                                  relative overflow-hidden">
                                        
                                        <!-- Efecto de brillo en hover -->
                                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/50 to-transparent translate-x-[-200%] group-hover:translate-x-[200%] transition-transform duration-700"></div>
                                        
                                        <!-- Número de opción -->
                                        <span class="relative flex items-center gap-3">
                                            <span class="flex-shrink-0 w-7 h-7 md:w-8 md:h-8 bg-gradient-to-br from-purple-400 to-purple-600 peer-checked:from-purple-600 peer-checked:to-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-xs md:text-sm shadow-md">
                                                {{ $index + 1 }}
                                            </span>
                                            <span class="flex-1">{{ $opcion }}</span>
                                            
                                            <!-- Checkmark cuando está seleccionado -->
                                            <svg class="hidden peer-checked:block w-5 h-5 text-purple-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        </span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    
                        <button type="submit" 
                                class="group relative w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold px-5 py-3 rounded-xl shadow-xl hover:shadow-2xl transition-all duration-300 text-base md:text-lg mt-4 overflow-hidden hover:scale-[1.02] active:scale-[0.98]">
                            <div class="absolute inset-0 bg-gradient-to-r from-purple-700 to-indigo-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <span class="relative flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Enviar mi Voto
                            </span>
                        </button>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-gray-500 text-lg font-medium">Esta pregunta no tiene opciones disponibles.</p>
                        </div>
                    @endif
                </form>
            @endif

            {{-- Botón de volver --}}
            <div class="text-center mt-5 pt-4 border-t border-gray-100">
                <a href="{{ route('encuestas.index') }}" 
                   class="inline-flex items-center gap-2 text-gray-500 hover:text-purple-600 text-sm font-medium transition-colors duration-300 group">
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver a encuestas
                </a>
            </div>

        </div>
        
    </div>
</div>
@endsection