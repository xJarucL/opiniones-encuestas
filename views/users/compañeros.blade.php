@extends('components.menu')

@section('title', 'Compañeros')

@section('content')
<div class="max-w-6xl mx-auto mt-10 p-4">

    <div class="mb-6">
        <a href="{{ route('inicio') }}"
           class="flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200 font-semibold">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 19l-7-7 7-7" />
            </svg>
            Panel
        </a>
    </div>

    <h1 class="text-3xl font-bold mb-8 text-black-700">Saluda a tus compañeros</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        @foreach($usuarios as $usuario)
            <a href="{{ route('compañero.show', $usuario->pk_usuario) }}"
               class="block rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-transform transform hover:scale-105 bg-gradient-to-br from-white-100 to-white-50">

                <div class="w-full flex justify-center pt-6">
                    <img src="{{ $usuario->img_user ? asset('storage/' . $usuario->img_user) : asset('img/default.webp') }}"
                         alt="{{ $usuario->username }}"
                         class="w-32 h-32 object-cover rounded-full border-4 border-white shadow-lg">
                </div>

                <div class="text-center p-4">
                    <h2 class="text-xl font-bold text-purple-700">{{ $usuario->username }}</h2>
                    <p class="text-gray-700 mt-1 text-sm">
                        {{ $usuario->nombres }} {{ $usuario->ap_paterno }} {{ $usuario->ap_materno ?? '' }}
                    </p>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection
