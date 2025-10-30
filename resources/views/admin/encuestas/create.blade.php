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
        <!-- Título -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Título de la Encuesta *
            </label>
            <input type="text" 
                    name="titulo" 
                    value="{{ old('titulo', $encuesta->titulo ?? '') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                    placeholder="Ej: Encuesta de Satisfacción 2025"
                    required>
        </div>

        <!-- Descripción -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Descripción
            </label>
            <textarea name="descripcion" 
                      rows="3"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                      placeholder="Describe el propósito de la encuesta">{{ old('descripcion', $encuesta->descripcion ?? '') }}</textarea>
        </div>

        <!-- Categoría -->
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

        <!-- Fechas de Activación (Reemplazo del Checkbox) -->
        <div class="border-t border-gray-200 pt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="fecha_inicio" class="block text-sm font-semibold text-gray-700 mb-2">
                    Fecha de Inicio (Opcional)
                </label>
                <input type="date" 
                       id="fecha_inicio" 
                       name="fecha_inicio" 
                       value="{{ old('fecha_inicio', $encuesta->fecha_inicio ?? null) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                @error('fecha_inicio')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Si se deja vacío, la encuesta inicia inmediatamente.</p>
            </div>

            <div>
                <label for="fecha_fin" class="block text-sm font-semibold text-gray-700 mb-2">
                    Fecha de Fin (Opcional)
                </label>
                <input type="date" 
                       id="fecha_fin" 
                       name="fecha_fin" 
                       value="{{ old('fecha_fin', $encuesta->fecha_fin ?? null) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                @error('fecha_fin')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">La fecha de fin debe ser igual o posterior a la fecha de inicio.</p>
            </div>
        </div>

        <!-- Preguntas (SOLO OPCIÓN MÚLTIPLE) -->
        <div class="border-t border-gray-200 pt-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Preguntas de Opción Múltiple</h3>
                <button type="button" 
                        onclick="agregarPregunta()"
                        class="bg-green-100 text-green-700 px-4 py-2 rounded-lg hover:bg-green-200 transition flex items-center gap-2 font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Agregar Pregunta
                </button>
            </div>

            <div id="preguntas-container" class="space-y-4">
                @if(isset($encuesta) && $encuesta->preguntas->count() > 0)
                    @foreach($encuesta->preguntas as $index => $pregunta)
                    @if($pregunta->tipo === 'multiple') 
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
                            
                            {{-- Input de Texto de la Pregunta --}}
                            <input type="text" 
                                    name="preguntas[{{ $index }}][texto]" 
                                    value="{{ old('preguntas.'.$index.'.texto', $pregunta->texto) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-3 focus:ring-2 focus:ring-green-500"
                                    placeholder="Escribe tu pregunta"
                                    required>
                                    
                            {{-- Input Oculto para asegurar el tipo 'multiple' --}}
                            <input type="hidden" name="preguntas[{{ $index }}][tipo]" value="multiple">

                            {{-- Contenedor de Opciones --}}
                            <div class="opciones-container mt-4 p-4 border border-gray-300 rounded-lg bg-white space-y-3">
                                <h5 class="text-sm font-semibold text-gray-600 mb-2">Opciones de Respuesta:</h5>
                                
                                @php
                                    // Usamos 'opciones_array' que se decodificó en el controlador
                                    $opciones = old('preguntas.'.$index.'.opciones', $pregunta->opciones_array ?? []);
                                    if(empty($opciones) || !is_array($opciones)) {
                                        $opciones = ['Opción A', 'Opción B']; // Placeholders si no hay datos
                                    }
                                @endphp

                                @foreach($opciones as $opcionTexto)
                                    <div class="flex gap-2 opcion-item">
                                        <input type="text"
                                               name="preguntas[{{ $index }}][opciones][]"
                                               value="{{ $opcionTexto }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-1 focus:ring-green-500"
                                               placeholder="Escribe una opción"
                                               required>
                                        <button type="button" onclick="eliminarOpcion(this)" class="text-red-500 hover:text-red-700 p-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                @endforeach

                                <button type="button" onclick="agregarOpcion(this)" class="text-sm text-green-600 hover:underline mt-2">
                                    + Añadir Opción
                                </button>
                            </div>

                        </div>
                    @endif
                    @endforeach
                @else
                {{-- Template inicial si no hay preguntas --}}
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
                    <input type="text" 
                            name="preguntas[0][texto]" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-3 focus:ring-2 focus:ring-green-500"
                            placeholder="Escribe tu pregunta"
                            required>
                            
                    <input type="hidden" name="preguntas[0][tipo]" value="multiple">

                    {{-- Contenedor de Opciones Inicial --}}
                    <div class="opciones-container mt-4 p-4 border border-gray-300 rounded-lg bg-white space-y-3">
                        <h5 class="text-sm font-semibold text-gray-600 mb-2">Opciones de Respuesta:</h5>
                        <div class="flex gap-2 opcion-item">
                            <input type="text" name="preguntas[0][opciones][]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-1 focus:ring-green-500" placeholder="Opción 1" required>
                            <button type="button" onclick="eliminarOpcion(this)" class="text-red-500 hover:text-red-700 p-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <div class="flex gap-2 opcion-item">
                            <input type="text" name="preguntas[0][opciones][]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-1 focus:ring-green-500" placeholder="Opción 2" required>
                            <button type="button" onclick="eliminarOpcion(this)" class="text-red-500 hover:text-red-700 p-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <button type="button" onclick="agregarOpcion(this)" class="text-sm text-green-600 hover:underline mt-2">
                            + Añadir Opción
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Botones -->
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

// Función para formatear la fecha actual (YYYY-MM-DD)
function getCurrentDate() {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

// Lógica de Validación de Fechas
document.addEventListener('DOMContentLoaded', function () {
    const fechaInicioInput = document.getElementById('fecha_inicio');
    const fechaFinInput = document.getElementById('fecha_fin');
    const currentDate = getCurrentDate();

    // 1. Establecer la fecha mínima de inicio a la fecha actual
    // Si estamos editando y la fecha guardada es anterior, la dejamos, pero para crear, forzamos el mínimo.
    if (!fechaInicioInput.value) { 
        fechaInicioInput.setAttribute('min', currentDate);
    }
    
    // 2. Listener para asegurar que la fecha de fin sea >= fecha de inicio
    function updateFechaFinMin() {
        const minDate = fechaInicioInput.value || currentDate;
        fechaFinInput.setAttribute('min', minDate);

        // Si la fecha de fin seleccionada es anterior a la nueva fecha mínima, la limpiamos.
        if (fechaFinInput.value && fechaFinInput.value < minDate) {
             fechaFinInput.value = minDate; // Forzar al mínimo o limpiar, dependiendo de la UX deseada
        }
    }

    // Ejecutar al inicio (por si hay valor precargado)
    updateFechaFinMin();
    
    // Ejecutar cada vez que cambia la fecha de inicio
    fechaInicioInput.addEventListener('change', updateFechaFinMin);
    
});
// FIN Lógica de Validación de Fechas

function agregarPregunta() {
    const container = document.getElementById('preguntas-container');
    const newIndex = preguntaIndex; 
    
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
            <input type="text" 
                    name="preguntas[${newIndex}][texto]" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-3 focus:ring-2 focus:ring-green-500"
                    placeholder="Escribe tu pregunta"
                    required>
            <input type="hidden" name="preguntas[${newIndex}][tipo]" value="multiple">

            <div class="opciones-container mt-4 p-4 border border-gray-300 rounded-lg bg-white space-y-3">
                <h5 class="text-sm font-semibold text-gray-600 mb-2">Opciones de Respuesta:</h5>
                <div class="flex gap-2 opcion-item">
                    <input type="text" name="preguntas[${newIndex}][opciones][]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-1 focus:ring-green-500" placeholder="Opción 1" required>
                    <button type="button" onclick="eliminarOpcion(this)" class="text-red-500 hover:text-red-700 p-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="flex gap-2 opcion-item">
                    <input type="text" name="preguntas[${newIndex}][opciones][]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-1 focus:ring-green-500" placeholder="Opción 2" required>
                    <button type="button" onclick="eliminarOpcion(this)" class="text-red-500 hover:text-red-700 p-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <button type="button" onclick="agregarOpcion(this)" class="text-sm text-green-600 hover:underline mt-2">
                    + Añadir Opción
                </button>
            </div>
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
        preguntaIndex = document.querySelectorAll('.pregunta-item').length;
        actualizarNumeracion();
    } else {
        alert('Debe haber al menos una pregunta en la encuesta');
    }
}

function agregarOpcion(button) {
    const container = button.closest('.opciones-container');
    const preguntaItem = button.closest('.pregunta-item');
    
    // Obtener el índice de la pregunta (necesario para el campo name)
    const indexMatch = preguntaItem.querySelector('input[type="text"]').name.match(/preguntas\[(\d+)\]/);
    const index = indexMatch ? indexMatch[1] : 0; 

    const opcionTemplate = `
        <div class="flex gap-2 opcion-item">
            <input type="text"
                   name="preguntas[${index}][opciones][]" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-1 focus:ring-green-500"
                   placeholder="Escribe una opción"
                   required>
            <button type="button" onclick="eliminarOpcion(this)" class="text-red-500 hover:text-red-700 p-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    `;
    // Insertamos la nueva opción justo antes del botón "Añadir Opción"
    button.insertAdjacentHTML('beforebegin', opcionTemplate);
}

function eliminarOpcion(button) {
    const opcionesContainer = button.closest('.opciones-container');
    const opciones = opcionesContainer.querySelectorAll('.opcion-item');
    
    // Obligar a que haya al menos dos opciones para Option Multiple
    if (opciones.length > 2) { 
        button.closest('.opcion-item').remove();
    } else {
        alert('Debe haber al menos dos opciones de respuesta.');
    }
}

function actualizarNumeracion() {
    const preguntas = document.querySelectorAll('.pregunta-item');
    preguntas.forEach((pregunta, index) => {
        // Actualizar el título de la pregunta
        pregunta.querySelector('h4').textContent = `Pregunta ${index + 1}`;
        
        // Actualizar los nombres de los inputs de preguntas y opciones
        pregunta.querySelector('input[name*="[texto]"]').name = `preguntas[${index}][texto]`;
        pregunta.querySelector('input[name*="[tipo]"]').name = `preguntas[${index}][tipo]`;
        
        // Actualizar los nombres de los inputs de opciones
        pregunta.querySelectorAll('.opcion-item input').forEach(input => {
            input.name = `preguntas[${index}][opciones][]`;
        });
    });
}
document.addEventListener('DOMContentLoaded', actualizarNumeracion);
</script>

@endsection
