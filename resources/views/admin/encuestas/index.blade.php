@extends('admin.admin-layout')

@section('title', 'Gestión de Encuestas')

{{-- Inclusión del CDN de SweetAlert2. Se usa @section('styles') porque a veces el @push('scripts')
   se ejecuta demasiado tarde. Si tu layout lo permite, es mejor moverlo directamente al layout. --}}
@section('styles')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Estilos de la alerta flotante */
        #mensaje {
            position: fixed; top: 20px; left: 50%; transform: translateX(-50%) translateY(-100%); z-index: 9999;
            min-width: 250px; max-width: 90%; text-align: center; padding: 12px 20px; border-radius: 8px;
            color: white; font-size: 0.875rem; font-weight: 500; box-shadow: 0 4px 6px rgba(0,0,0,0.2);
            opacity: 0; animation: slideDown 0.5s forwards;
        }
        .success { background-color: #16a34a; }
        .error { background-color: #dc2626; }
        /* Animaciones */
        @keyframes slideDown { from { transform: translateX(-50%) translateY(-100%); opacity: 0; } to { transform: translateX(-50%) translateY(0); opacity: 1; } }
        @keyframes slideUp { from { transform: translateX(-50%) translateY(0); opacity: 1; } to { transform: translateX(-50%) translateY(-100%); opacity: 0; } }
    </style>
@endsection


@section('content')

    {{-- HEADER PRINCIPAL --}}
    <header class="mb-8 animate-fade-slide">
        <div class="bg-gradient-to-r from-purple-600 to-purple-800 rounded-2xl shadow-xl p-8 text-white">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-4xl font-bold mb-2">Gestión de Encuestas</h1>
                    <p class="text-purple-100 text-lg">Administra, crea y edita las encuestas del sistema</p>
                </div>
                <a href="{{ route('admin.encuestas.create') }}" 
                    class="inline-flex items-center px-6 py-3 bg-white text-purple-700 font-semibold rounded-xl shadow-lg hover:shadow-2xl hover:scale-105 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nueva Encuesta
                </a>
            </div>
        </div>
    </header>

    {{-- Contenedor de Alerta Flotante (x-msj-alert) --}}
    <div class="mb-6 animate-fade-slide relative z-50" style="animation-delay: 0.1s;">
        <x-msj-alert />
    </div>

    {{-- Bloque de Estadísticas Rápidas ELIMINADO --}}

    {{-- INICIO DEL CONTENEDOR DE LA TABLA Y LISTADO --}}
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden animate-fade-slide" style="animation-delay: 0.2s;">
        
        <div class="bg-gradient-to-r from-purple-50 to-purple-100 px-6 py-4 border-b border-purple-200">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center">
                    <div class="bg-purple-600 p-2 rounded-lg mr-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Listado de Encuestas</h2>
                        <p class="text-sm text-gray-600">Total de encuestas: <span class="font-semibold text-purple-600">{{ $encuestas->total() }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Título</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Categoría</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($encuestas as $encuesta)
                        <tr class="hover:bg-purple-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">#{{ $encuesta->id }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $encuesta->titulo }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($encuesta->descripcion, 50) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $encuesta->categoria->nombre ?? 'Sin categoría' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
    {{-- Usamos la columna real de la DB: 'estado' --}}
    @if($encuesta->estado) 
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            Activa
        </span>
    @else
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            Inactiva
        </span>
    @endif
</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">{{ $encuesta->created_at->format('d/m/Y') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.encuestas.show', $encuesta->id) }}" 
                                        class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 group"
                                        title="Ver encuesta">
                                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>

                                    <a href="{{ route('admin.encuestas.edit', $encuesta->id) }}" 
                                        class="inline-flex items-center px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-200 group"
                                        title="Editar encuesta">
                                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>

                                    <form action="{{ route('admin.encuestas.destroy', $encuesta->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                data-swal-form
                                                data-swal-title="¿Eliminar esta encuesta?"
                                                data-swal-text="Esta acción no se puede revertir. Se eliminarán todos los datos relacionados."
                                                data-swal-icon="warning"
                                                data-swal-confirm="Sí, eliminar"
                                                data-swal-cancel="Cancelar"
                                                data-swal-color="#e53e3e"
                                                class="inline-flex items-center px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors duration-200 group"
                                                title="Eliminar encuesta">
                                            <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="bg-gray-100 p-6 rounded-full mb-4">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-600 text-lg font-medium mb-2">No hay encuestas registradas</p>
                                    <p class="text-gray-500 text-sm">Crea tu primera encuesta para comenzar</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if ($encuestas->hasPages())
            <div class="px-6 py-4 bg-white border-t border-gray-200">
                {{ $encuestas->links() }}
            </div>
        @endif
    </div>
    {{-- FIN DEL CONTENEDOR DE LA TABLA Y LISTADO --}}

@endsection

@push('scripts')
{{-- SweetAlert2 Scripts y Lógica de Alerta Flotante --}}
<script>
    // Lógica para cerrar la alerta flotante (x-msj-alert)
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

    // Lógica para SweetAlert2 (maneja data-swal-form)
    document.addEventListener('click', function (e) {
        const button = e.target.closest('[data-swal-form]');

        if (button) {
            e.preventDefault();
            
            if (typeof Swal === 'undefined') {
                console.error('SweetAlert2 no está cargado. El botón de eliminación no funcionará.');
                // Fallback para al menos tener una confirmación si SweetAlert2 falla
                const form = button.closest('form');
                if (confirm(button.dataset.swalText || '¿Estás seguro de continuar?')) {
                    form.submit();
                }
                return;
            }
            
            const form = button.closest('form');
            const swalTitle = button.dataset.swalTitle || '¿Estás seguro?';
            const swalText = button.dataset.swalText || 'Esta acción no se puede revertir.';
            const swalIcon = button.dataset.swalIcon || 'warning';
            const swalConfirm = button.dataset.swalConfirm || 'Sí, eliminar';
            const swalColor = button.dataset.swalColor || '#e53e3e';

            Swal.fire({
                title: swalTitle,
                text: swalText,
                icon: swalIcon,
                showCancelButton: true,
                confirmButtonColor: swalColor,
                cancelButtonColor: '#6e7881', 
                confirmButtonText: swalConfirm,
                cancelButtonText: 'Cancelar' 
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // ¡Envía el formulario DELETE!
                }
            });
        }
    });
</script>
@endpush