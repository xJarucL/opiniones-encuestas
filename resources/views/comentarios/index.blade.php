@extends('admin.admin-layout')

@section('title', 'Moderación de Comentarios')

{{-- ====================================================== --}}
{{-- AGREGADO: Cargar SweetAlert2 desde CDN en el head --}}
{{-- ====================================================== --}}
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')



    <!-- Mensajes de alerta -->
    <div class="mb-6 animate-fade-slide" style="animation-delay: 0.1s;">
        <x-msj-alert />
    </div>



    <!-- Lista de comentarios -->
    <div class="space-y-4 animate-fade-slide" style="animation-delay: 0.3s;">
        @forelse($comentarios as $comentario)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 {{ $comentario->estatus === 'oculto' ? 'border-2 border-red-200' : '' }}">
                
                <!-- Header del comentario -->
                <div class="bg-gradient-to-r {{ $comentario->estatus === 'oculto' ? 'from-red-50 to-red-100' : 'from-gray-50 to-gray-100' }} px-6 py-4 border-b {{ $comentario->estatus === 'oculto' ? 'border-red-200' : 'border-gray-200' }}">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center space-x-3">
                            <!-- Avatar del usuario -->
                            <div class="flex-shrink-0">
                                @if($comentario->autor && $comentario->autor->img_user)
                                    <img src="{{ asset('storage/'.$comentario->autor->img_user) }}" alt="{{ $comentario->autor->username }}" class="h-12 w-12 rounded-full object-cover border-2 border-yellow-300">
                                @else
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center text-white font-bold text-lg shadow-md">
                                        {{ strtoupper(substr($comentario->autor->nombres ?? 'U', 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Info del usuario -->
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $comentario->autor->nombres ?? 'Usuario desconocido' }}</h3>
                                <p class="text-sm text-gray-600">{{ $comentario->autor->email ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Estado -->
                        <div class="flex items-center space-x-2">
                            @if($comentario->estatus === 'oculto')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                    Oculto
                                </span>
                            @else

                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contenido del comentario -->
                <div class="px-6 py-5">
                    <div class="mb-4">
                        <p class="text-gray-800 text-base leading-relaxed {{ $comentario->estatus === 'oculto' ? 'line-through opacity-60' : '' }}">
                            {{ $comentario->contenido }}
                        </p>
                    </div>

                    <!-- Metadata -->
                    <div class="flex items-center space-x-4 text-sm text-gray-500 mb-4">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ optional($comentario->fecha_creacion)->format('d/m/Y H:i') }}
                        </div>
                        
                        @if($comentario->fk_perfil_user)
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Perfil: {{ $comentario->perfil->nombres ?? 'N/A' }}
                        </div>
                        @endif

                        @if($comentario->fk_coment_respuesta)
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                            </svg>
                            Respuesta
                        </div>
                        @endif
                    </div>

                    <!-- Acciones de moderación -->
                    <div class="flex items-center space-x-3 pt-4 border-t border-gray-200">
                        @if($comentario->estatus === 'oculto')
                            <!-- Mostrar comentario -->
                            <form action="{{ route('admin.comentarios.show', $comentario->pk_comentario) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 bg-green-100 text-green-700 font-medium rounded-lg hover:bg-green-200 transition-all duration-200 hover:scale-105 group">
                                    <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Mostrar Comentario
                                </button>
                            </form>
                        @endif

                        <!-- Eliminar comentario -->
                        {{-- ====================================================== --}}
                        {{-- ESTA ES LA RUTA CORREGIDA --}}
                        {{-- ====================================================== --}}
                        <form action="{{ route('admin.comentarios.destroy', $comentario->pk_comentario) }}" method="POST" class="inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    data-swal-form
                                    data-swal-title="¿Eliminar permanentemente?"
                                    data-swal-text="Esta acción NO se puede revertir. El comentario se borrará."
                                    data-swal-icon="error"
                                    data-swal-confirm="Sí, eliminar"
                                    data-swal-cancel="Cancelar"
                                    data-swal-color="#dc2626"
                                    class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-all duration-200 hover:scale-105 group">
                                <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <div class="flex flex-col items-center justify-center">
                    <div class="bg-yellow-100 p-6 rounded-full mb-4">
                        <svg class="w-12 h-12 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-600 text-lg font-medium mb-2">No hay comentarios para moderar</p>
                    <p class="text-gray-500 text-sm">Los comentarios aparecerán aquí cuando los usuarios comiencen a interactuar</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if($comentarios->hasPages())
    <div class="mt-8 animate-fade-slide" style="animation-delay: 0.4s;">
        <div class="bg-white rounded-xl shadow-md p-4">
            {{ $comentarios->appends(request()->query())->links() }}
        </div>
    </div>
    @endif

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8 animate-fade-slide" style="animation-delay: 0.5s;">
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase">Total Comentarios</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalCount }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-xl">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                </div>
            </div>

    </div>

@endsection

{{-- ====================================================== --}}
{{-- CARGAR SWEETALERT2 ANTES DEL SCRIPT --}}
{{-- ====================================================== --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const swalButtons = document.querySelectorAll('[data-swal-form]');

        swalButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault(); 

                const form = this.closest('form');
                if (!form) return;

                const title = this.dataset.swalTitle || '¿Estás seguro?';
                const text = this.dataset.swalText || 'Esta acción no se puede revertir.';
                const icon = this.dataset.swalIcon || 'warning';
                const confirmButtonText = this.dataset.swalConfirm || 'Sí, hazlo';
                const cancelButtonText = this.dataset.swalCancel || 'Cancelar';
                const confirmButtonColor = this.dataset.swalColor || '#3085d6';

                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonColor: confirmButtonColor,
                    cancelButtonColor: '#6e7881',
                    confirmButtonText: confirmButtonText,
                    cancelButtonText: cancelButtonText
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush

