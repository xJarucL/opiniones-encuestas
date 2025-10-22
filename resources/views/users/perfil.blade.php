@extends('components.menu')

@section('title', 'Mi perfil')

@section('content')
<div class="max-w-4xl mx-auto mt-10 relative p-8 bg-gradient-to-r from-purple-100 to-purple-50 rounded-3xl shadow-2xl">

    @if(auth()->id() == $usuario->pk_usuario)
        <a href="{{ route('usuarios.edit', $usuario->pk_usuario) }}"
           class="absolute top-6 right-6 inline-flex items-center px-4 py-2 bg-purple-600 text-white font-semibold rounded-full shadow-lg
                  hover:bg-purple-700 hover:scale-105 transform transition duration-300">
            Editar perfil
        </a>
    @endif

    <div class="mb-6">
        <a href="{{ url()->previous() }}"
           class="flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200 font-semibold">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 19l-7-7 7-7" />
            </svg>
            Volver
        </a>
    </div>

    <div class="flex flex-col md:flex-row items-center md:items-start md:space-x-12">

        <div class="relative">
            <div class="w-44 h-44 md:w-52 md:h-52 rounded-full bg-gradient-to-tr from-purple-500 to-pink-500 p-1 shadow-xl">
                <img
                    src="{{ $usuario->img_user ? asset('storage/' . $usuario->img_user) : asset('img/default.webp') }}"
                    alt="Foto de perfil"
                    class="w-full h-full object-cover rounded-full border-4 border-white shadow-lg"
                />
            </div>
        </div>

        <div class="flex-1 mt-8 md:mt-0 space-y-6">
            <h1 class="text-4xl font-extrabold text-purple-700">{{ $usuario->username }}</h1>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-gray-700 text-lg">
                <div class="bg-white p-4 rounded-xl shadow hover:shadow-lg transition">
                    <span class="font-semibold text-purple-600">Nombre completo:</span>
                    <p class="mt-1">{{ $usuario->nombres }} {{ $usuario->ap_paterno }} {{ $usuario->ap_materno ?? '' }}</p>
                </div>

                <div class="bg-white p-4 rounded-xl shadow hover:shadow-lg transition">
                    <span class="font-semibold text-purple-600">Correo:</span>
                    <p class="mt-1">{{ $usuario->email }}</p>
                </div>
            </div>
        </div>
    </div>

            {{-- COMENTARIOS --}}
        <section class="mt-10">
        <h2 class="text-xl font-bold mb-4 text-purple-700">Comentarios</h2>

        <form method="POST" action="{{ route('comentarios.store', $usuario->pk_usuario) }}" class="mb-6">
            @csrf
            <textarea name="contenido" required maxlength="1000" class="w-full border rounded p-3"
                    placeholder="Escribe un comentario..."></textarea>
            <label class="inline-flex items-center mt-2 gap-2">
            <input type="checkbox" name="anonimo" value="1"> Publicar como anónimo
            </label>
            <button class="mt-3 bg-purple-600 text-white px-4 py-2 rounded">Comentar</button>
        </form>

        <div class="space-y-4">
            @foreach($comentarios as $comentario)
            @include('comentarios._comentario', ['comentario' => $comentario])
            @endforeach
        </div>
        </section>


</div>
@endsection



