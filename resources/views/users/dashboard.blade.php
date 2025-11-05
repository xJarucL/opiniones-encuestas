<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'purple-primary': '#8b5cf6',
                        'purple-dark': '#7c3aed',
                        'purple-light': '#a78bfa',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    
    {{-- Navegación con gradiente morado --}}
    <nav class="bg-gradient-to-r from-purple-600 to-purple-700 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">Encuestas y Opiniones</h1>
                
                <div class="flex items-center space-x-6">
                    <a href="{{ route('inicio') }}" class="text-white hover:text-purple-200 transition font-medium">Inicio</a>
                    <a href="{{ route('perfil') }}" class="text-white hover:text-purple-200 transition font-medium">Mi Perfil</a>
                    <a href="{{ route('compañeros') }}" class="text-white hover:text-purple-200 transition font-medium">Compañeros</a>
                    
                    <div class="flex items-center space-x-3">
                        @if($usuario->img_user)
                            <img src="{{ asset('storage/' . $usuario->img_user) }}" 
                                 alt="Avatar" 
                                 class="w-10 h-10 rounded-full border-2 border-white shadow-md">
                        @else
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center border-2 border-purple-300 shadow-md">
                                <span class="text-lg font-bold text-purple-600">
                                    {{ substr($usuario->nombres, 0, 1) }}
                                </span>
                            </div>
                        @endif
                        <span class="text-white font-medium">{{ $usuario->username }}</span>
                    </div>
                    
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-white text-purple-600 hover:bg-purple-50 px-4 py-2 rounded-lg font-medium transition shadow-md">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        {{-- Encabezado de bienvenida --}}
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-purple-700 mb-2">¡Bienvenido, {{ $usuario->nombres }}!</h1>
        </div>

        {{-- Tarjetas de acceso rápido --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            
            {{-- Tarjeta: Mi Perfil --}}
            <a href="{{ route('perfil') }}" class="group bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 border-2 border-transparent hover:border-purple-500">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center group-hover:bg-purple-200 transition">
                        <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800 ml-3 group-hover:text-purple-600 transition">Mi Perfil</h2>
                </div>
                <p class="text-gray-600 text-sm">Ver y editar mi información personal y comentarios</p>
            </a>

            {{-- Tarjeta: Compañeros --}}
            <a href="{{ route('compañeros') }}" class="group bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 border-2 border-transparent hover:border-purple-500">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center group-hover:bg-purple-200 transition">
                        <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800 ml-3 group-hover:text-purple-600 transition">Compañeros</h2>
                </div>
                <p class="text-gray-600 text-sm">Explora perfiles y deja comentarios a otros usuarios</p>
            </a>

            {{-- ====================================================== --}}
            {{-- CORRECCIÓN: Apuntar a 'encuestas.index' (la ruta de usuario) --}}
            {{-- ====================================================== --}}
            <a href="{{ route('encuestas.index') }}" class="group bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 border-2 border-transparent hover:border-purple-500">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center group-hover:bg-purple-200 transition">
                        <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800 ml-3 group-hover:text-purple-600 transition">Responder Encuestas</h2>
                </div>
                <p class="text-gray-600 text-sm">Participa en las encuestas activas y da tu opinión.</p>
            </a>
            
        </div>
    </div>

    {{-- Footer --}}
    <footer class="bg-white mt-12 py-6 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-gray-600">&copy; {{ date('Y') }} Encuestas y Opiniones. Todos los derechos reservados.</p>
        </div>
    </footer>

</body>
</html>

