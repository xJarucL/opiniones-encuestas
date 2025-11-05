@extends('admin.admin-layout')

@section('title', isset($encuesta) ? 'Editar Encuesta' : 'Nueva Encuesta')

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.encuestas.index') }}" class="text-green-600 hover:text-green-700 mb-2 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Volver a Encuestas
    </a>
    <h1 class="text-3xl font-bold text-gray-800 mt-2">{{ isset($encuesta) ? 'Editar Encuesta' : 'Nueva Encuesta' }}</h1>
    <p class="text-gray-600 mt-1">{{ isset($encuesta) ? 'Modifica los datos de la encuesta' :'Completa el formulario para crear una nueva encuesta' }}</p>
</div>

@if($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ isset($encuesta) ? route('admin.encuestas.update', $encuesta->id) : route('admin.encuestas.store') }}" 
      method="POST" class="bg-white rounded-xl shadow-lg p-8">
    @csrf
    @if(isset($encuesta))
        @method('PUT')
    @endif

    <div class="space-y-6">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Título de la Encuesta *
            </label>
            <input type="text" 
                   name="titulo" 
                   value="{{ old('titulo', $encuesta->titulo ?? '') }}"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                   placeholder="Ej: Nominaciones Anuales 2025"
                   required>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Descripción
            </label>
            <textarea name="descripcion" 
                      rows="3"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                      placeholder="Describe el propósito de esta votación">{{ old('descripcion', $encuesta->descripcion ?? '') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Categoría *
            </label>
            <select name="categoria_id" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                    required>
                <option value="">Seleccionar categoría</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" 
                            {{ old('categoria_id', $encuesta->categoria_id ?? '') == $categoria->id ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
            <p class="text-sm text-gray-500 mt-1">
                ¿No encuentras la categoría? <a href="{{ route('admin.categorias.index') }}" class="text-green-600 hover:underline">Crear nueva categoría</a>
            </p>
        </div>

        <div class="border-t border-gray-200 pt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="fecha_inicio" class="block text-sm font-semibold text-gray-700 mb-2">
                    Fecha de Inicio (Opcional)
                </label>
                <input type="date" 
                       id="fecha_inicio" 
                       name="fecha_inicio" 
                       value="{{ old('fecha_inicio', isset($encuesta->fecha_inicio) ? \Carbon\Carbon::parse($encuesta->fecha_inicio)->format('Y-m-d') : null) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
            </div>

            <div>
                <label for="fecha_fin" class="block text-sm font-semibold text-gray-700 mb-2">
                    Fecha de Fin (Opcional)
                </label>
                <input type="date" 
                       id="fecha_fin" 
                       name="fecha_fin" 
                       value="{{ old('fecha_fin', isset($encuesta->fecha_fin) ? \Carbon\Carbon::parse($encuesta->fecha_fin)->format('Y-m-d') : null) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
            </div>
        </div>

        <div class="border-t border-gray-200 pt-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Preguntas de Nominación</h3>
                <button type="button" 
                        onclick="agregarPregunta()"
                        class="bg-green-100 text-green-700 px-4 py-2 rounded-lg hover:bg-green-200 transition flex items-center gap-2 font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Agregar Pregunta
                </button>
            </div>
            
            <p class="text-sm text-gray-500 mb-4 -mt-2">Las opciones de respuesta para estas preguntas serán automáticamente todos los usuarios registrados.</p>

            <div id="preguntas-container" class="space-y-4">
                
                {{-- Caso 1: Editando una encuesta existente --}}
                @if(isset($encuesta) && $encuesta->preguntas->count() > 0)
                    @foreach($encuesta->preguntas as $index => $pregunta)
                        <div class="pregunta-item bg-gray-50 p-6 rounded-lg border-2 border-gray-200">
                            <div class="flex items-start justify-between mb-3">
                                <h4 class="font-semibold text-gray-700">Pregunta {{ $index + 1 }}</h4>
                                <button type="button" 
                                        onclick="eliminarPregunta(this)"
                                        class="text-red-600 hover:text-red-700 p-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Texto de la Pregunta</label>
                            <input type="text" 
                                   name="preguntas[{{ $index }}][texto]" 
                                   value="{{ old('preguntas.'.$index.'.texto', $pregunta->texto) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-3 focus:ring-2 focus:ring-green-500"
                                   placeholder="Ej: ¿Quién es el mejor compañero?"
                                   required>
                                   
                            <input type="hidden" name="preguntas[{{ $index }}][tipo]" value="nominados">

                            {{-- El contenedor de opciones ha sido ELIMINADO --}}
                        </div>
                    @endforeach
                
                {{-- Caso 2: Creando una nueva encuesta (template inicial) --}}
                @else
                    <div class="pregunta-item bg-gray-50 p-6 rounded-lg border-2 border-gray-200">
                        <div class="flex items-start justify-between mb-3">
                            <h4 class="font-semibold text-gray-700">Pregunta 1</h4>
                            <button type="button" 
                                    onclick="eliminarPregunta(this)"
                                    class="text-red-600 hover:text-red-700 p-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Texto de la Pregunta</label>
                        <input type="text" 
                               name="preguntas[0][texto]" 
                               value="{{ old('preguntas.0.texto') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-3 focus:ring-2 focus:ring-green-500"
                               placeholder="Ej: ¿Quién es el mejor compañero?"
                               required>
                        
                        <input type="hidden" name="preguntas[0][tipo]" value="nominados">
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="flex gap-4 mt-8">
        <button type="submit" 
                class="flex-1 bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition-colors shadow-lg">
            {{ isset($encuesta) ? 'Actualizar Encuesta' : 'Crear Encuesta' }}
        </button>
        <a href="{{ route('admin.encuestas.index') }}" 
           class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-lg font-semibold transition-colors text-center">
            Cancelar
        </a>
    </div>
</form>

<script>
let preguntaIndex = {{ isset($encuesta) ? $encuesta->preguntas->count() : 1 }};

// La variable 'allUsers' y 'buildUserOptions' han sido ELIMINADAS

// Lógica de Validación de Fechas
document.addEventListener('DOMContentLoaded', function () {
    const fechaInicioInput = document.getElementById('fecha_inicio');
    const fechaFinInput = document.getElementById('fecha_fin');
    
    function getCurrentDate() {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    const currentDate = getCurrentDate();
    
    if (fechaInicioInput && fechaFinInput) {
        if (!fechaInicioInput.value) { 
            fechaInicioInput.setAttribute('min', currentDate);
        }
        function updateFechaFinMin() {
            const minDate = fechaInicioInput.value || currentDate;
            fechaFinInput.setAttribute('min', minDate);
            if (fechaFinInput.value && fechaFinInput.value < minDate) {
                 fechaFinInput.value = minDate;
            }
        }
        updateFechaFinMin();
        fechaInicioInput.addEventListener('change', updateFechaFinMin);
    }
    actualizarNumeracion();
});

// --- Funciones de Preguntas (SIMPLIFICADAS) ---

function agregarPregunta() {
    const container = document.getElementById('preguntas-container');
    const newIndex = preguntaIndex; 

    // Plantilla de la nueva pregunta (SIN OPCIONES)
    const nuevaPregunta = `
        <div class="pregunta-item bg-gray-50 p-6 rounded-lg border-2 border-gray-200 animate-fade-slide">
            <div class="flex items-start justify-between mb-3">
                <h4 class="font-semibold text-gray-700">Pregunta ${newIndex + 1}</h4>
                <button type="button" 
                        onclick="eliminarPregunta(this)"
                        class="text-red-600 hover:text-red-700 p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Texto de la Pregunta</label>
            <input type="text" 
                   name="preguntas[${newIndex}][texto]" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-3 focus:ring-2 focus:ring-green-500"
                   placeholder="Ej: ¿Quién es el más colaborativo?"
                   required>
            <input type="hidden" name="preguntas[${newIndex}][tipo]" value="nominados">
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', nuevaPregunta);
    preguntaIndex++;
    actualizarNumeracion();
}

function eliminarPregunta(button) {
    const preguntas = document.querySelectorAll('.pregunta-item');
    if (preguntas.length > 1) {
        button.closest('.pregunta-item').remove();
        actualizarNumeracion();
    } else {
        alert('Debe haber al menos una pregunta en la encuesta');
    }
}

function actualizarNumeracion() {
    const preguntas = document.querySelectorAll('.pregunta-item');
    preguntaIndex = preguntas.length; 

    preguntas.forEach((pregunta, index) => {
        pregunta.querySelector('h4').textContent = `Pregunta ${index + 1}`;
        
        const inputTexto = pregunta.querySelector('input[name*="[texto]"]');
        if (inputTexto) {
            inputTexto.name = `preguntas[${index}][texto]`;
        }

        const inputTipo = pregunta.querySelector('input[name**="[tipo]"]');
        if (inputTipo) {
            inputTipo.name = `preguntas[${index}][tipo]`;
        }
    });
}
</script>

@endsection