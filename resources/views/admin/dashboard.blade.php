<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        @keyframes fade-slide {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-slide {
            animation: fade-slide 0.5s ease-out forwards;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    
    <!-- Navegación Superior -->
    <nav class="bg-gradient-to-r from-purple-600 to-purple-800 shadow-lg mb-8">
        <div class="container mx-auto px-4 py-4 max-w-7xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="bg-white/20 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-white">Panel de Administración</h2>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 text-white hover:bg-white/20 rounded-lg transition font-medium">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.encuestas.index') }}" class="px-4 py-2 text-white hover:bg-white/20 rounded-lg transition font-medium">
                        Encuestas
                    </a>
                    <a href="{{ route('admin.categorias.index') }}" class="px-4 py-2 text-white hover:bg-white/20 rounded-lg transition font-medium">
                        Categorías
                    </a>
                    <a href="{{ route('admin.comentarios.index') }}" class="px-4 py-2 text-white hover:bg-white/20 rounded-lg transition font-medium">
                        Comentarios
                    </a>
                    <a href="{{ route('usuarios.lista') }}" class="px-4 py-2 text-white hover:bg-white/20 rounded-lg transition font-medium">
                        Usuarios
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white hover:bg-red-600 rounded-lg transition font-medium">
                            Salir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Contenedor Principal -->
    <main class="container mx-auto px-4 pb-8 max-w-7xl">
        
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
            <a href="{{ route('usuarios.lista') }}" 
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

        <!-- Sección: Módulos Activos -->
        <div class="bg-white rounded-2xl shadow-lg p-6 animate-fade-slide" style="animation-delay: 0.3s;">
            <div class="flex items-center mb-6">
                <div class="bg-green-100 p-3 rounded-xl mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Más Funciones Disponibles</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                {{-- Tarjeta: Encuestas --}}
                <a href="{{ route('admin.encuestas.index') }}" 
                   class="group border-2 border-green-300 rounded-xl p-6 hover:border-green-500 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-start">
                        <div class="bg-green-100 p-3 rounded-lg mr-4 group-hover:bg-green-500 transition-colors">
                            <svg class="w-6 h-6 text-green-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-800 mb-1 group-hover:text-green-600 transition-colors">
                                Gestión de Encuestas
                            </h3>
                            <p class="text-sm text-gray-600 mb-3">
                                Creación y gestión de encuestas y visualización de resultados.
                            </p>
                            <div class="flex items-center text-green-600 font-medium group-hover:translate-x-2 transition-transform">
                                <span class="text-sm">Acceder</span>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>

                {{-- Tarjeta: Categorías --}}
                <a href="{{ route('admin.categorias.index') }}" 
                   class="group border-2 border-indigo-300 rounded-xl p-6 hover:border-indigo-500 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-start">
                        <div class="bg-indigo-100 p-3 rounded-lg mr-4 group-hover:bg-indigo-500 transition-colors">
                            <svg class="w-6 h-6 text-indigo-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-800 mb-1 group-hover:text-indigo-600 transition-colors">
                                Gestión de Categorías
                            </h3>
                            <p class="text-sm text-gray-600 mb-3">
                                Administrar las categorías disponibles para las encuestas u opiniones.
                            </p>
                            <div class="flex items-center text-indigo-600 font-medium group-hover:translate-x-2 transition-transform">
                                <span class="text-sm">Acceder</span>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>

            </div>
        </div>
        
    </main>
    
    <!-- Footer -->
    <footer class="mt-12 py-6 bg-white border-t border-gray-200">
        <div class="container mx-auto px-4 max-w-7xl text-center text-gray-600 text-sm">
            © {{ date('Y') }} Sistema de Opiniones y Encuestas
        </div>
    </footer>
    
</body>
</html>