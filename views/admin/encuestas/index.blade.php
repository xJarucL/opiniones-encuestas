@extends('admin.admin-layout')

@section('title', 'Gestión de Encuestas')

@section('content')

<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="text-green-600 hover:text-green-700 mb-2 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver al Dashboard
            </a>
            <h1 class="text-3xl font-bold text-gray-800 mt-2">Gestión de Encuestas</h1>
            <p class="text-gray-600 mt-1">Crea, edita y visualiza resultados de encuestas</p>
        </div>
        <a href="{{ route('admin.encuestas.create') }}" 
           class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 font-semibold transition-colors shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Nueva Encuesta
        </a>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 animate-fade-slide">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            {{ session('success') }}
        </div>
    </div>
@endif

<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-xl shadow-lg">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-green-100 text-sm mb-1">Total Encuestas</p>
                <p class="text-3xl font-bold">{{ $encuestas->total() }}</p>
            </div>
            <svg class="w-8 h-8 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
        </div>
    </div>
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-xl shadow-lg">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-blue-100 text-sm mb-1">Activas</p>
                <p class="text-3xl font-bold">{{ $encuestas->where('estado', 1)->count() }}</p>
            </div>
            <svg class="w-8 h-8 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
            </svg>
        </div>
    </div>
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-6 rounded-xl shadow-lg">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-purple-100 text-sm mb-1">Respuestas</p>
                <p class="text-3xl font-bold">{{ $totalRespuestas ?? 0 }}</p>
            </div>
            <svg class="w-8 h-8 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        </div>
    </div>
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-6 rounded-xl shadow-lg">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-orange-100 text-sm mb-1">Categorías</p>
                <p class="text-3xl font-bold">{{ $totalCategorias ?? 0 }}</p>
            </div>
            <svg class="w-8 h-8 text-orange-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
        </div>
    </div>
</div>

<!-- Lista de encuestas -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b-2 border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Título</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Categoría</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Estado</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Preguntas</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Respuestas</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Creada</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($encuestas as $encuesta)
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $encuesta->titulo }}</td>
                    <td class="px-6 py-4">
                        @if($encuesta->categoria)
                        <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm">
                            {{ $encuesta->categoria->nombre }}
                        </span>
                        @else
                        <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-sm">Sin categoría</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.encuestas.cambiar-estado', $encuesta->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-3 py-1 rounded-full text-sm font-medium transition-colors {{ $encuesta->estado ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                {{ $encuesta->estado ? 'Activa' : 'Inactiva' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $encuesta->preguntas_count ?? 0 }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $encuesta->respuestas_count ?? 0 }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $encuesta->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2 justify-end">
                            <a href="{{ route('admin.encuestas.show', $encuesta->id) }}"
                               class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Ver resultados">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('admin.encuestas.edit', $encuesta->id) }}"
                               class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="{{ route('admin.encuestas.destroy', $encuesta->id) }}" method="POST" 
                                  onsubmit="return confirm('¿Estás seguro de eliminar esta encuesta?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-lg font-semibold">No hay encuestas creadas</p>
                        <p class="text-sm mt-1">Comienza creando tu primera encuesta</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($encuestas->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $encuestas->links() }}
    </div>
    @endif
</div>

@endsection