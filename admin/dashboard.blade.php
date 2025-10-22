@extends('admin.admin-layout')

@section('title', 'Dashboard')

@section('content')

    <!-- Header con animación -->
    <header class="mb-8 animate-fade-slide">
        <div class="bg-gradient-to-r from-purple-600 to-purple-800 rounded-2xl shadow-xl p-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">Panel de Administración</h1>
                    <p class="text-purple-100 text-lg">
                        Bienvenido, Administrador. Gestiona todas las áreas del sistema desde aquí.
                    </p>
                </div>
                <div class="hidden md:block">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Tarjetas de gestión -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        
        {{-- Tarjeta: Gestión de Usuarios --}}
        <a href="{{ route('lista_usuarios') }}" 
           class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden animate-fade-slide"
           style="animation-delay: 0.1s;">
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="bg-purple-100 p-4 rounded-xl group-hover:bg-purple-600 transition-colors duration-300">
                        <svg class="w-8 h-8 text-purple-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0l4-4m2 2h.01"></path>
                        </svg>
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold text-purple-700 bg-purple-100 rounded-full">
                        PERSONAS
                    </span>
                </div>
                
                <h3 class="text-2xl font-bold text-gray-800 mb-2 group-hover:text-purple-700 transition-colors">
                    Gestión de Usuarios
                </h3>
                <p class="text-gray-600 mb-4">
                    Administra usuarios activos e inactivos, asigna roles y registra nuevos miembros del sistema.
                </p>
                
                <div class="flex items-center text-purple-600 font-medium group-hover:translate-x-2 transition-transform">
                    <span>Ir a usuarios</span>
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </div>
            </div>
            <div class="h-2 bg-gradient-to-r from-purple-500 to-purple-700"></div>
        </a>

        {{-- Tarjeta: Moderación de Opiniones --}}
        <a href="{{ route('admin.comentarios.index') }}" 
           class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden animate-fade-slide"
           style="animation-delay: 0.2s;">
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="bg-yellow-100 p-4 rounded-xl group-hover:bg-yellow-500 transition-colors duration-300">
                        <svg class="w-8 h-8 text-yellow-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">
                        CONTENIDO
                    </span>
                </div>
                
                <h3 class="text-2xl font-bold text-gray-800 mb-2 group-hover:text-yellow-600 transition-colors">
                    Moderación de Opiniones
                </h3>
                <p class="text-gray-600 mb-4">
                    Revisa, oculta o muestra comentarios y opiniones. Mantén el contenido apropiado y seguro.
                </p>
                
                <div class="flex items-center text-yellow-600 font-medium group-hover:translate-x-2 transition-transform">
                    <span>Ir a comentarios</span>
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </div>
            </div>
            <div class="h-2 bg-gradient-to-r from-yellow-400 to-yellow-600"></div>
        </a>

    </div>

    <!-- Sección: Próximamente -->
    <div class="bg-white rounded-2xl shadow-lg p-6 animate-fade-slide" style="animation-delay: 0.3s;">
        <div class="flex items-center mb-6">
            <div class="bg-gray-100 p-3 rounded-xl mr-4">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Funciones en Desarrollo</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            {{-- Placeholder: Encuestas --}}
            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 opacity-60 hover:opacity-100 transition-opacity">
                <div class="flex items-start">
                    <div class="bg-green-100 p-3 rounded-lg mr-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">Gestión de Encuestas</h3>
                        <p class="text-sm text-gray-600">Crea, edita y analiza encuestas. Visualiza resultados en tiempo real.</p>
                        <span class="inline-block mt-2 px-3 py-1 text-xs font-semibold text-gray-600 bg-gray-200 rounded-full">
                            Próximamente
                        </span>
                    </div>
                </div>
            </div>

            {{-- Placeholder: Categorías --}}
            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 opacity-60 hover:opacity-100 transition-opacity">
                <div class="flex items-start">
                    <div class="bg-indigo-100 p-3 rounded-lg mr-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">Gestión de Categorías</h3>
                        <p class="text-sm text-gray-600">Organiza y administra categorías para encuestas y contenido del sistema.</p>
                        <span class="inline-block mt-2 px-3 py-1 text-xs font-semibold text-gray-600 bg-gray-200 rounded-full">
                            Próximamente
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection