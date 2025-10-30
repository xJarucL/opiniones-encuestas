@extends('admin.admin-layout')

@section('title', 'Gestión de Categorías')

@section('content')

<style>
/* Estilo para el fondo del modal con efecto blur */
.modal-backdrop {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}
</style>

<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="text-purple-600 hover:text-purple-700 mb-2 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver al Dashboard
            </a>
            <h1 class="text-3xl font-bold text-gray-800 mt-2">Gestión de Categorías</h1>
            <p class="text-gray-600 mt-1">Organiza las categorías para las encuestas</p>
        </div>
        <button onclick="abrirModal()" 
                class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 font-semibold transition-colors shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Nueva Categoría
        </button>
    </div>
</div>

{{-- Alerta del componente --}}
<div class="mb-6">
    <x-msj-alert />
</div>

@if($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
        <div class="flex items-center mb-2">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-semibold">Errores de validación:</span>
        </div>
        <ul class="list-disc list-inside ml-7">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-6 rounded-xl shadow-lg">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-purple-100 text-sm mb-1">Total Categorías</p>
                <p class="text-3xl font-bold">{{ $categorias->count() }}</p>
            </div>
            <svg class="w-10 h-10 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
        </div>
    </div>
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white p-6 rounded-xl shadow-lg">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-indigo-100 text-sm mb-1">Encuestas Asociadas</p>
                <p class="text-3xl font-bold">{{ $categorias->sum('encuestas_count') }}</p>
            </div>
            <svg class="w-10 h-10 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
        </div>
    </div>

</div>

<!-- Grid de categorías -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($categorias as $categoria)
    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 animate-fade-slide" style="animation-delay: {{ $loop->index * 0.05 }}s;">
        <div class="flex items-start justify-between mb-4">
            <div class="bg-purple-100 p-3 rounded-lg">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
            </div>
            <div class="flex gap-2">
                <button onclick='editarCategoria({{ $categoria->id }}, "{{ addslashes($categoria->nombre) }}", "{{ addslashes($categoria->descripcion) }}")'
                        class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" 
                        title="Editar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </button>
                <form action="{{ route('admin.categorias.destroy', $categoria->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" 
                            data-swal-form
                            data-swal-title="¿Eliminar Categoría?"
                            data-swal-text="Esta acción eliminará la categoría. Las encuestas asociadas quedarán sin categoría."
                            data-swal-icon="warning"
                            data-swal-confirm="Sí, eliminar"
                            data-swal-cancel="Cancelar"
                            data-swal-color="#dc2626"
                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                            title="Eliminar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $categoria->nombre }}</h3>
        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $categoria->descripcion }}</p>
        <div class="flex items-center justify-between">
            <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm font-semibold">
                {{ $categoria->encuestas_count ?? 0 }} encuestas
            </span>
            <span class="text-xs text-gray-500">
                Creada: {{ $categoria->created_at->format('d/m/Y') }}
            </span>
        </div>
    </div>
    @empty
    <div class="col-span-full bg-white rounded-xl shadow-lg text-center py-16 px-6">
        <div class="max-w-md mx-auto">
            <div class="bg-purple-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">No hay categorías creadas</h3>
            <p class="text-gray-600 mb-6">Las categorías te ayudan a organizar tus encuestas de manera eficiente</p>
            <button onclick="abrirModal()" 
                    class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Crear Primera Categoría
            </button>
        </div>
    </div>
    @endforelse
</div>

<!-- Modal para crear/editar categoría -->
<div id="modalCategoria" class="hidden fixed inset-0 bg-black/20 modal-backdrop flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full transform transition-all animate-fade-slide">
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 p-6 rounded-t-xl">
            <div class="flex items-center justify-between text-white">
                <h2 id="modalTitulo" class="text-2xl font-bold">Nueva Categoría</h2>
                <button onclick="cerrarModal()" class="hover:bg-white/20 p-2 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <form id="formCategoria" method="POST" class="p-6">
            @csrf
            <input type="hidden" id="metodo" name="_method" value="POST">
            <input type="hidden" id="categoriaId" name="categoria_id">
            
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nombre de la Categoría *
                    </label>
                    <input type="text" 
                           id="nombre" 
                           name="nombre" 
                           maxlength="255"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"
                           placeholder="Ej: Servicio al Cliente"
                           required>
                    <p class="text-xs text-gray-500 mt-1">Máximo 255 caracteres</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Descripción *
                    </label>
                    <textarea id="descripcion" 
                              name="descripcion" 
                              rows="4"
                              maxlength="500"
                              class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition resize-none"
                              placeholder="Describe el propósito y alcance de esta categoría..."
                              required></textarea>
                    <p class="text-xs text-gray-500 mt-1">Máximo 500 caracteres</p>
                </div>
            </div>

            <div class="flex gap-3 mt-8">
                <button type="submit" 
                        class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-3 rounded-lg font-semibold transition-colors shadow-lg hover:shadow-xl">
                    <span id="botonTexto">Crear Categoría</span>
                </button>
                <button type="button" 
                        onclick="cerrarModal()"
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-lg font-semibold transition-colors">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Funciones del Modal
function abrirModal() {
    document.getElementById('modalCategoria').classList.remove('hidden');
    document.getElementById('modalTitulo').textContent = 'Nueva Categoría';
    document.getElementById('botonTexto').textContent = 'Crear Categoría';
    document.getElementById('formCategoria').action = '{{ route("admin.categorias.store") }}';
    document.getElementById('metodo').value = 'POST';
    document.getElementById('categoriaId').value = '';
    document.getElementById('nombre').value = '';
    document.getElementById('descripcion').value = '';
    document.getElementById('nombre').focus();
}

function cerrarModal() {
    document.getElementById('modalCategoria').classList.add('hidden');
}

function editarCategoria(id, nombre, descripcion) {
    document.getElementById('modalCategoria').classList.remove('hidden');
    document.getElementById('modalTitulo').textContent = 'Editar Categoría';
    document.getElementById('botonTexto').textContent = 'Actualizar Categoría';
    document.getElementById('formCategoria').action = `/admin/categorias/${id}`;
    document.getElementById('metodo').value = 'PUT';
    document.getElementById('categoriaId').value = id;
    document.getElementById('nombre').value = nombre;
    document.getElementById('descripcion').value = descripcion;
    document.getElementById('nombre').focus();
}

// Cerrar modal al hacer clic fuera
document.getElementById('modalCategoria')?.addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModal();
    }
});

// Cerrar modal con tecla ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('modalCategoria').classList.contains('hidden')) {
        cerrarModal();
    }
});

// SweetAlert2 para confirmación de eliminación
document.addEventListener('click', function (e) {
    const button = e.target.closest('[data-swal-form]');
    if (button) {
        e.preventDefault();
        const form = button.closest('form');
        
        Swal.fire({
            title: button.dataset.swalTitle || '¿Estás seguro?',
            text: button.dataset.swalText || 'No podrás revertir esta acción.',
            icon: button.dataset.swalIcon || 'warning',
            showCancelButton: true,
            confirmButtonColor: button.dataset.swalColor || '#dc2626',
            cancelButtonColor: '#6e7881',
            confirmButtonText: button.dataset.swalConfirm || 'Sí, continuar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
});
</script>
@endpush