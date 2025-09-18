@extends('components.menu')

@section('title', 'Prueba Tailwind')

@section('content')
    <div class="bg-white p-8 rounded-2xl shadow-lg text-center">
        <h1 class="text-4xl font-bold text-blue-600 mb-4">
            🚀 TailwindCSS funcionando en hola
        </h1>
        <p class="text-gray-700">Ya puedes usar clases de Tailwind en tus vistas Blade.</p>
    </div>
@endsection
