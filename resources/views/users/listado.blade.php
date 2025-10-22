@extends('components.menu')

@section('title', 'Usuarios')

@section('content')
    <div class="mt-6 px-4 max-w-6xl mx-auto w-full">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3">
                <a href="{{ route('inicio') }}"
                class="flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 19l-7-7 7-7" />
                    </svg>
                    Inicio
                </a>

                <h1 class="text-2xl font-bold text-gray-800">Usuarios</h1>
            </div>

            <a href="/usuarios/registro"
            class="flex items-center bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg shadow transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo usuario
            </a>
        </div>


        <x-msj-alert />

        <div class="overflow-x-auto bg-white shadow-md rounded-lg table-container">
            <table class="min-w-full text-sm text-left text-gray-700">
                <thead class="bg-purple-600 text-white uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-3">Nombre de usuario</th>
                        <th class="px-6 py-3">Nombre completo</th>
                        <th class="px-6 py-3">Correo electrónico</th>
                        <th class="px-6 py-3">Tipo de usuario</th>
                        <th class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @if($usuarios->isEmpty())
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">
                                No hay usuarios para mostrar
                            </td>
                        </tr>
                    @else
                        @foreach ($usuarios as $usuario)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 flex items-center space-x-3">
                                    <img
                                        src="{{ $usuario->img_user ? asset('storage/'.$usuario->img_user) : asset('img/default.webp') }}"
                                        alt="Usuario"
                                        class="w-10 h-10 rounded-full object-cover border"
                                    />
                                    <span>{{ $usuario->username }}</span>
                                </td>
                                <td class="px-6 py-4">{{ $usuario->nombres }} {{ $usuario->ap_paterno }} {{ $usuario->ap_materno }}</td>
                                <td class="px-6 py-4">{{ $usuario->email }}</td>
                                <td class="px-6 py-4 relative overflow-visible">
                                    <form action="{{ route('usuarios.cambiar-tipo', $usuario->pk_usuario) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div x-data="{ open: false, selected: {{ $usuario->fk_tipo_user ?? 'null' }}, selectedText: '{{ $usuario->fk_tipo_user ? $usuario->tipo_usuario->nombre : 'Seleccionar tipo' }}' }" class="relative w-48">
                                            <button type="button"
                                                    @click="open = !open"
                                                    class="w-full bg-purple-800 text-white rounded-lg shadow px-4 py-2 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-purple-500">
                                                <span x-text="selectedText"></span>
                                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </button>

                                            <div x-show="open"
                                                @click.outside="open = false"
                                                class="fixed mt-1 w-48 bg-purple-800 text-white border border-purple-700 rounded-lg shadow-lg max-h-60 overflow-auto z-50"
                                                :style="`top: ${$el.parentElement.getBoundingClientRect().bottom}px; left: ${$el.parentElement.getBoundingClientRect().left}px; width: ${$el.parentElement.offsetWidth}px;`">
                                                @foreach ($tipos_usuario as $tipo)
                                                    <button type="submit"
                                                            name="fk_tipo_user"
                                                            value="{{ $tipo->pk_tipo_user }}"
                                                            @click="selected = {{ $tipo->pk_tipo_user }}; selectedText = '{{ $tipo->nombre }}'; open = false;"
                                                            class="w-full text-left px-4 py-2 hover:bg-purple-500 hover:text-white transition-colors duration-150">
                                                        {{ $tipo->nombre }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </form>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        @if ($usuario->trashed())
                                            <form action="{{ route('usuarios.restaurar', $usuario->pk_usuario) }}" method="POST">
                                                @csrf
                                                <button type="button"
                                                    data-swal-form
                                                    data-swal-title="¿Restaurar este usuario?"
                                                    data-swal-text="El usuario volverá a tener acceso al sistema."
                                                    data-swal-icon="question"
                                                    data-swal-confirm="Sí, restaurar"
                                                    data-swal-cancel="Cancelar"
                                                    data-swal-color="#38a169"
                                                    class="flex items-center bg-green-500 text-white px-3 py-1 rounded-lg hover:bg-green-600 transition min-w-[80px] justify-center">

                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10v11h11m4-4l5 5m-5-5v5h5"/>
                                                    </svg>
                                                    Restaurar
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{route('usuarios.edit', $usuario -> pk_usuario) }}"
                                            class="flex items-center bg-blue-500 text-white px-3 py-1 rounded-lg hover:bg-blue-600 transition min-w-[80px] justify-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                </svg>
                                                Editar
                                            </a>

                                            <form action="{{ route('usuarios.eliminar', $usuario->pk_usuario) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    data-swal-form
                                                    data-swal-title="¿Eliminar este usuario?"
                                                    data-swal-text="Esta acción deshabilitará el acceso del usuario al sistema."
                                                    data-swal-icon="warning"
                                                    data-swal-confirm="Sí, eliminar"
                                                    data-swal-cancel="Cancelar"
                                                    data-swal-color="#e53e3e"
                                                    class="flex items-center bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition min-w-[80px] justify-center">

                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a2 2 0 012 2v2H8V5a2 2 0 012-2z"/>
                                                    </svg>
                                                    Deshabilitar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $usuarios->links() }}
        </div>

        <div class="mt-6 flex justify-end">
            @if (request()->routeIs('lista_usuarios'))
                <a href="{{ route('lista_usuarios_inactivos') }}"
                class="flex items-center bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg shadow transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a2 2 0 012 2v2H8V5a2 2 0 012-2z"/>
                    </svg>
                    Ver usuarios inactivos
                </a>
            @elseif (request()->routeIs('lista_usuarios_inactivos'))
                <a href="{{ route('lista_usuarios') }}"
                class="flex items-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7"/>
                    </svg>
                    Ver usuarios activos
                </a>
            @endif
        </div>

    </div>
@endsection
