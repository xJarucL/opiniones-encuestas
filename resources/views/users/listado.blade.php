@extends('admin.admin-layout')

@section('title', $mostrarInactivos ? 'Usuarios Inactivos' : 'Usuarios Activos')

@section('content')

    <header class="mb-8 animate-fade-slide">
        <div class="bg-gradient-to-r {{ $mostrarInactivos ? 'from-gray-600 to-gray-800' : 'from-purple-600 to-purple-800' }} rounded-2xl shadow-xl p-8 text-white">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-4xl font-bold mb-2">
                        {{ $mostrarInactivos ? 'Usuarios Inactivos' : 'Gestión de Usuarios Activos' }}
                    </h1>
                    <p class="{{ $mostrarInactivos ? 'text-gray-100' : 'text-purple-100' }} text-lg">
                        {{ $mostrarInactivos ? 'Gestiona y reactiva usuarios deshabilitados del sistema' : 'Administra roles, edita información y registra nuevos usuarios' }}
                    </p>
                </div>
                
                @if(!$mostrarInactivos)
                    <a href="{{ route('usuarios.registro') }}" 
                       class="inline-flex items-center px-6 py-3 bg-white text-purple-700 font-semibold rounded-xl shadow-lg hover:shadow-2xl hover:scale-105 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Nuevo Usuario
                    </a>
                @else
                    <a href="{{ route('usuarios.lista') }}" 
                       class="inline-flex items-center px-6 py-3 bg-white text-gray-700 font-semibold rounded-xl shadow-lg hover:shadow-2xl hover:scale-105 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z"></path>
                        </svg>
                        Ver Activos
                    </a>
                @endif
            </div>
        </div>
    </header>

    {{-- Contenedor de Alerta Corregido --}}
    <div class="mb-6 animate-fade-slide relative z-50" style="animation-delay: 0.1s;">
        <x-msj-alert />
    </div>

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden animate-fade-slide" style="animation-delay: 0.2s;">
        
        <div class="bg-gradient-to-r {{ $mostrarInactivos ? 'from-gray-50 to-gray-100' : 'from-purple-50 to-purple-100' }} px-6 py-4 border-b {{ $mostrarInactivos ? 'border-gray-200' : 'border-purple-200' }}">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center">
                    <div class="{{ $mostrarInactivos ? 'bg-gray-600' : 'bg-purple-600' }} p-2 rounded-lg mr-3">
                        @if($mostrarInactivos)
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                            </svg>
                        @else
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Listado de Usuarios {{ $mostrarInactivos ? 'Inactivos' : 'Activos' }}</h2>
                        <p class="text-sm text-gray-600">Total de usuarios {{ $mostrarInactivos ? 'inactivos' : 'activos' }}: <span class="font-semibold {{ $mostrarInactivos ? 'text-gray-600' : 'text-purple-600' }}">{{ $usuarios->total() }}</span></p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2">
                    <a href="{{ $mostrarInactivos ? route('usuarios.lista') : route('usuarios.inactivos') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Ver {{ $mostrarInactivos ? 'Activos' : 'Inactivos' }}
                    </a>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Usuario</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nombre Completo</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipo de Usuario</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($usuarios as $usuario)
                        <tr class="hover:{{ $mostrarInactivos ? 'bg-gray-50' : 'bg-purple-50' }} transition-colors duration-150 {{ $mostrarInactivos ? 'opacity-75' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12 relative">
                                        @if($usuario->img_user)
                                            <img src="{{ asset('storage/'.$usuario->img_user) }}" 
                                                 alt="{{ $usuario->username }}"
                                                 class="h-12 w-12 rounded-full object-cover border-2 {{ $mostrarInactivos ? 'border-gray-300 grayscale' : 'border-purple-200' }}">
                                        @else
                                            <div class="h-12 w-12 rounded-full bg-gradient-to-br {{ $mostrarInactivos ? 'from-gray-400 to-gray-600' : 'from-purple-400 to-purple-600' }} flex items-center justify-center text-white font-bold text-lg shadow-md">
                                                {{ strtoupper(substr($usuario->username ?? $usuario->nombres, 0, 1)) }}
                                            </div>
                                        @endif
                                        @if($mostrarInactivos)
                                            <!-- Indicador de inactivo -->
                                            <div class="absolute -top-1 -right-1 bg-red-500 rounded-full p-1">
                                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $usuario->username }}</div>
                                        @if($mostrarInactivos)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                Inactivo
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">
                                    {{ $usuario->nombres }} {{ $usuario->ap_paterno }} {{ $usuario->ap_materno }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">{{ $usuario->email }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(!$mostrarInactivos)
                                    <form action="{{ route('usuarios.cambiar-tipo', $usuario->pk_usuario) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        {{-- Dropdown de Alpine.js para cambiar el tipo de usuario --}}
                                        <div x-data="{ 
                                            open: false, 
                                            selected: {{ $usuario->fk_tipo_user ?? 'null' }}, 
                                            selectedText: '{{ $usuario->fk_tipo_user ? $usuario->tipo_usuario->nombre : 'Seleccionar tipo' }}' 
                                        }" class="relative w-48">
                                            <button type="button"
                                                    @click="open = !open"
                                                    class="w-full text-white rounded-lg shadow px-4 py-2 flex justify-between items-center focus:outline-none focus:ring-2 transition-all duration-200"
                                                    :class="selectedText.includes('Administrador') ? 'bg-purple-600 hover:bg-purple-700 focus:ring-purple-500' : 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500'">
                                                <span x-text="selectedText" class="text-sm font-medium"></span>
                                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </button>

                                            <div x-show="open"
                                                 @click.outside="open = false"
                                                 x-transition:enter="transition ease-out duration-200"
                                                 x-transition:enter-start="opacity-0 scale-95"
                                                 x-transition:enter-end="opacity-100 scale-100"
                                                 class="absolute mt-1 w-48 bg-white border border-gray-200 rounded-lg shadow-xl z-50 overflow-hidden">
                                                @foreach ($tipos_usuario as $tipo)
                                                    <button type="submit"
                                                            name="fk_tipo_user"
                                                            value="{{ $tipo->pk_tipo_user }}"
                                                            @click="selected = {{ $tipo->pk_tipo_user }}; selectedText = '{{ $tipo->nombre }}'; open = false;"
                                                            class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition-colors duration-150 flex items-center">
                                                        @if(Str::contains(strtolower($tipo->nombre), 'admin'))
                                                            <svg class="w-4 h-4 mr-2 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                            </svg>
                                                        @else
                                                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        @endif
                                                        {{ $tipo->nombre }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </form>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        @if($usuario->fk_tipo_user)
                                            @if(Str::contains(strtolower($usuario->tipo_usuario->nombre), 'admin'))
                                                <svg class="w-4 h-4 mr-1 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 mr-1 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                            {{ $usuario->tipo_usuario->nombre }}
                                        @else
                                            Sin tipo asignado
                                        @endif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    @if(!$mostrarInactivos)
                                        <!-- Botón Editar -->
                                        <a href="{{ route('usuarios.edit', $usuario->pk_usuario) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-200 group"
                                           title="Editar usuario">
                                            <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>

                                        <!-- Botón Eliminar (Deshabilitar) -->
                                        <form action="{{ route('usuarios.eliminar', $usuario->pk_usuario) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    data-swal-form
                                                    data-swal-title="¿Deshabilitar este usuario?" {{-- Texto Cambiado --}}
                                                    data-swal-text="Esta acción moverá al usuario a la lista de inactivos." {{-- Texto Cambiado --}}
                                                    data-swal-icon="warning"
                                                    data-swal-confirm="Sí, deshabilitar" {{-- Texto Cambiado --}}
                                                    data-swal-cancel="Cancelar"
                                                    data-swal-color="#e53e3e"
                                                    class="inline-flex items-center px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors duration-200 group"
                                                    title="Deshabilitar usuario"> {{-- Title Cambiado --}}
                                                <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <!-- Botón Activar Usuario -->
                                        <form action="{{ route('usuarios.restaurar', $usuario->pk_usuario) }}" method="POST" class="inline"> 
                                            @csrf
                                           
                                            <button type="button"
                                                    data-swal-form
                                                    data-swal-title="¿Activar este usuario?"
                                                    data-swal-text="El usuario podrá acceder nuevamente al sistema."
                                                    data-swal-icon="question"
                                                    data-swal-confirm="Sí, activar"
                                                    data-swal-cancel="Cancelar"
                                                    data-swal-color="#10b981" {{-- Verde --}}
                                                    class="inline-flex items-center px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors duration-200 group"
                                                    title="Activar usuario">
                                                <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="font-medium">Activar</span>
                                            </button>
                                        </form>


                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    data-swal-form
                                                    data-swal-title="¿Eliminar permanentemente?"
                                                    data-swal-text="Esta acción NO se puede revertir. Se eliminarán todos los datos del usuario."
                                                    data-swal-icon="error"
                                                    data-swal-confirm="Sí, eliminar"
                                                    data-swal-cancel="Cancelar"
                                                    data-swal-color="#dc2626"
                                                    class="inline-flex items-center px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors duration-200 group"
                                                    title="Eliminar permanentemente">
                                                <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="{{ $mostrarInactivos ? 'bg-green-100' : 'bg-gray-100' }} p-6 rounded-full mb-4">
                                        @if($mostrarInactivos)
                                            <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <p class="text-gray-600 text-lg font-medium mb-2">
                                        {{ $mostrarInactivos ? '¡No hay usuarios inactivos!' : 'No hay usuarios activos' }}
                                    </p>
                                    <p class="text-gray-500 text-sm">
                                        {{ $mostrarInactivos ? 'Todos los usuarios están activos en el sistema' : 'Registra tu primer usuario para comenzar' }}
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación colocada en un pie de tabla limpio --}}
        @if ($usuarios->hasPages())
            <div class="px-6 py-4 bg-white border-t border-gray-200">
                {{ $usuarios->links() }}
            </div>
        @endif

    </div>

    <!-- Estadísticas rápidas -->
    @if(!$mostrarInactivos)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8 animate-fade-slide" style="animation-delay: 0.3s;">
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 uppercase">Total Activos</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $usuarios->total() }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-xl">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 uppercase">Administradores</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalAdmins }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 uppercase">Usuarios Regulares</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalRegulares }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-xl">
                        <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    @else {{-- Si estamos mostrando inactivos --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8 animate-fade-slide" style="animation-delay: 0.3s;">
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 uppercase">Total Inactivos</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $usuarios->total() }}</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-xl">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 uppercase">Acción Recomendada</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">Revisar y reactivar</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    @endif {{-- Cierre del @if para las estadísticas --}}

@endsection

{{-- ====================================================== --}}
{{-- SCRIPT DE SWEETALERT2 --}}
{{-- ====================================================== --}}
@push('scripts')
<script>
    // Espera a que todo el contenido de la página esté listo
    document.addEventListener('DOMContentLoaded', function () {
        
        // 1. Busca TODOS los botones que tengan el atributo 'data-swal-form'
        const swalButtons = document.querySelectorAll('[data-swal-form]');

        // 2. Recorre cada botón encontrado
        swalButtons.forEach(button => {
            
            // 3. Añade un "escuchador" de clics a cada uno
            button.addEventListener('click', function (e) {
                
                // Previene que el formulario se envíe por sí solo
                e.preventDefault(); 

                // 4. Busca el formulario <form> más cercano al botón
                const form = this.closest('form');
                if (!form) return; // Si no hay form, no hace nada

                // 5. Lee todos los atributos 'data-swal-...' del botón
                const title = this.dataset.swalTitle || '¿Estás seguro?';
                const text = this.dataset.swalText || 'Esta acción no se puede revertir.';
                const icon = this.dataset.swalIcon || 'warning';
                const confirmButtonText = this.dataset.swalConfirm || 'Sí, hazlo';
                const cancelButtonText = this.dataset.swalCancel || 'Cancelar';
                const confirmButtonColor = this.dataset.swalColor || '#3085d6'; // Color por defecto de Swal

                // 6. Muestra la alerta de SweetAlert con los datos leídos
                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonColor: confirmButtonColor,
                    cancelButtonColor: '#6e7881', // Gris
                    confirmButtonText: confirmButtonText,
                    cancelButtonText: cancelButtonText
                }).then((result) => {
                    // 7. Si el usuario hace clic en "Sí, confirmar"
                    if (result.isConfirmed) {
                        // Envía el formulario
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush

