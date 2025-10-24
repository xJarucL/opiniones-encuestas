@extends('admin.admin-layout') {{-- Extiende el layout administrativo que tiene la barra lateral --}}

@section('title', 'Panel de Administración')

@section('content')

    <header class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Panel de Administración Centralizado</h1>
        <p class="text-gray-600">
            Bienvenido, Administrador. Utiliza el menú de la izquierda para acceder a la gestión de datos.
            Aquí tienes un resumen rápido de las áreas principales:
        </p>
    </header>

    <div class="bg-white p-6 rounded-xl shadow-lg">
        <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Áreas de Gestión y Moderación</h2>
        
        <ul class="divide-y divide-gray-200">
            
            {{-- Enlace a Usuarios (Ruta: lista_usuarios) --}}
            <li class="py-4 flex justify-between items-center hover:bg-gray-50 transition duration-150 px-2 rounded-lg">
                <div class="flex items-center">
                    {{-- Ícono de usuarios --}}
                    <svg class="w-6 h-6 text-purple-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm6-10a2 2 0 00-2-2h-2a2 2 0 00-2 2v4a2 2 0 002 2h2a2 2 0 002-2v-4z"></path></svg>
                    <div>
                        <a href="{{ route('lista_usuarios') }}" class="text-lg font-medium text-gray-800 hover:text-purple-700">Gestión de Usuarios</a>
                        <p class="text-sm text-gray-500">Ver listado de usuarios activos e inactivos, cambiar roles y registrar nuevos.</p>
                    </div>
                </div>
                <span class="text-xs font-semibold text-purple-500 bg-purple-100 px-3 py-1 rounded-full">PERSONAS</span>
            </li>
            
            {{-- Enlace a Opiniones/Comentarios (Ruta: admin.comments.index) --}}
            <li class="py-4 flex justify-between items-center hover:bg-gray-50 transition duration-150 px-2 rounded-lg">
                <div class="flex items-center">
                    {{-- Ícono de comentarios --}}
                    <svg class="w-6 h-6 text-yellow-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4z"></path></svg>
                    <div>
                        <a href="{{ route('admin.comentarios.index') }}" class="text-lg font-medium text-gray-800 hover:text-yellow-700">Moderación de Opiniones/Comentarios</a>
                        <p class="text-sm text-gray-500">Ocultar o mostrar comentarios reportados o inapropiados.</p>
                    </div>
                </div>
                <span class="text-xs font-semibold text-yellow-500 bg-yellow-100 px-3 py-1 rounded-full">CONTENIDO</span>
            </li>

            {{-- Placeholder para Encuestas --}}
            <li class="py-4 flex justify-between items-center px-2 rounded-lg opacity-60">
                <div class="flex items-center">
                    {{-- Ícono de encuestas --}}
                    <svg class="w-6 h-6 text-green-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v14M9 19c0 1.105-1.79 2-4 2s-4-.895-4-2 1.79-2 4-2 4 .895 4 2zm12-3c0 1.105-1.79 2-4 2s-4-.895-4-2 1.79-2 4-2 4 .895 4 2zM9 10l12-3"></path></svg>
                    <div>
                        <span class="text-lg font-medium text-gray-800">Gestión de Encuestas</span>
                        <p class="text-sm text-gray-500">Creación y gestión de encuestas y visualización de resultados.</p>
                    </div>
                </div>
                <span class="text-xs font-semibold text-gray-400 bg-gray-100 px-3 py-1 rounded-full">PENDIENTE</span>
            </li>
            
            {{-- Placeholder para Categorías --}}
            <li class="py-4 flex justify-between items-center px-2 rounded-lg opacity-60">
                <div class="flex items-center">
                    {{-- Ícono de categorías --}}
                    <svg class="w-6 h-6 text-indigo-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    <div>
                        <span class="text-lg font-medium text-gray-800">Categorías</span>
                        <p class="text-sm text-gray-500">Administrar las categorías disponibles para las encuestas u opiniones.</p>
                    </div>
                </div>
                <span class="text-xs font-semibold text-gray-400 bg-gray-100 px-3 py-1 rounded-full">PENDIENTE</span>
            </li>
            
        </ul>
        
    </div>

@endsection
