<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Administrativo')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
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
                    <a href="{{ route('lista_usuarios') }}" class="px-4 py-2 text-white hover:bg-white/20 rounded-lg transition font-medium">
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
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="mt-12 py-6 bg-white border-t border-gray-200">
        <div class="container mx-auto px-4 max-w-7xl text-center text-gray-600 text-sm">
            © {{ date('Y') }} Sistema de Opiniones y Encuestas
        </div>
    </footer>
    
</body>
</html>