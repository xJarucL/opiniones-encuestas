@extends('components.menu')

@section('title', 'Panel')

@section('content')
<div class="min-h-screen bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto">

        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">
                ¡Hola, {{ Auth::user()->username }}!
            </h1>
            @if(Auth::user()->fk_tipo_user == 1)
                <span class="px-3 py-1 rounded-full
                            bg-purple-100 text-purple-600 font-semibold text-sm">
                    Rol: {{ Auth::user()->fk_tipo_user == 1 ? 'Administrador' : 'Usuario' }}
                </span>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            @if(Auth::user()->fk_tipo_user == 1)
                <a href="/usuarios"
                class="block bg-white rounded-xl shadow-md p-6 hover:shadow-lg
                        hover:bg-purple-50 transition">
                    <h2 class="text-xl font-semibold text-gray-700 mb-2">Usuarios</h2>
                    <p class="text-gray-500 text-sm">Gestiona los usuarios registrados.</p>
                </a>
            @endif

            <a href="#"
               class="block bg-white rounded-xl shadow-md p-6 hover:shadow-lg
                      hover:bg-purple-50 transition">
                <h2 class="text-xl font-semibold text-gray-700 mb-2">Categorías</h2>
                <p class="text-gray-500 text-sm">Gestiona las categorías para las encuestas.</p>
            </a>

            <a href="#"
               class="block bg-white rounded-xl shadow-md p-6 hover:shadow-lg
                      hover:bg-purple-50 transition">
                <h2 class="text-xl font-semibold text-gray-700 mb-2">Encuestas</h2>
                <p class="text-gray-500 text-sm">Gestiona las encuestas.</p>
            </a>

            <a href="{{route('compañeros')}}"
            class="block bg-white rounded-xl shadow-md p-6 hover:shadow-lg
                    hover:bg-purple-50 transition">
                <h2 class="text-xl font-semibold text-gray-700 mb-2">Compañeros</h2>
                <p class="text-gray-500 text-sm">Saluda a tus compañeros!</p>
            </a>

        </div>

    </div>
</div>
@endsection
