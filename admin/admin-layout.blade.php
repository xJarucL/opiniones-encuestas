<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración | @yield('title')</title>
    {{-- Asegurando la carga de assets y Tailwind --}}
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/funciones.js', 'resources/js/sweetalert.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100">
    
    <div class="flex h-screen bg-gray-100">
        
        <!-- Barra Lateral de Navegación (Sidebar) -->
        <aside class="w-64 bg-purple-700 text-white p-4 shadow-xl flex flex-col">
            <h2 class="text-2xl font-semibold mb-6 border-b border-purple-500 pb-2">Admin Panel</h2>
            <nav class="space-y-2 flex-grow">
                
                {{-- 1. Dashboard --}}
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center p-3 rounded-lg transition duration-150 ease-in-out hover:bg-purple-600 
                          {{ request()->routeIs('admin.dashboard') ? 'bg-purple-800 font-bold' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    Dashboard
                </a>

                {{-- 2. Gestión de Usuarios (Ruta existente: lista_usuarios) --}}
                <span class="text-sm font-light text-purple-200 mt-4 block pt-4 border-t border-purple-600">Gestión de Personas</span>
                <a href="{{ route('lista_usuarios') }}" 
                   class="flex items-center p-3 rounded-lg transition duration-150 ease-in-out hover:bg-purple-600 
                          {{ request()->routeIs(['lista_usuarios', 'lista_usuarios_inactivos', 'usuarios.edit', 'usuarios.registro']) ? 'bg-purple-600 font-medium' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm6-10a2 2 0 00-2-2h-2a2 2 0 00-2 2v4a2 2 0 002 2h2a2 2 0 002-2v-4z"></path></svg>
                    Usuarios Activos
                </a>
                
                {{-- Sub-Filtro de Usuarios Inactivos --}}
                <a href="{{ route('lista_usuarios_inactivos') }}" 
                   class="flex items-center pl-10 pr-3 py-2 text-sm rounded-lg transition duration-150 ease-in-out hover:bg-purple-600 
                          {{ request()->routeIs('lista_usuarios_inactivos') ? 'bg-purple-600' : 'text-purple-200' }}">
                    Usuarios Inactivos (Eliminados)
                </a>

                {{-- 3. Moderación de Contenido (Ruta nueva: admin.comments.index) --}}
                <span class="text-sm font-light text-purple-200 mt-4 block pt-4 border-t border-purple-600">Contenido y Moderación</span>
                <a href="{{ route('admin.comments.index') }}" 
                   class="flex items-center p-3 rounded-lg transition duration-150 ease-in-out hover:bg-purple-600 
                          {{ request()->routeIs('admin.comments.index') ? 'bg-purple-600 font-medium' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4z"></path></svg>
                    Opiniones/Comentarios
                </a>
                
                {{-- Enlaces Placeholder para el futuro --}}
                <a href="#" class="flex items-center p-3 rounded-lg opacity-50 cursor-not-allowed text-purple-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v14M9 19c0 1.105-1.79 2-4 2s-4-.895-4-2 1.79-2 4-2 4 .895 4 2zm12-3c0 1.105-1.79 2-4 2s-4-.895-4-2 1.79-2 4-2 4 .895 4 2zM9 10l12-3"></path></svg>
                    Gestión de Encuestas
                </a>
                <a href="#" class="flex items-center p-3 rounded-lg opacity-50 cursor-not-allowed text-purple-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    Categorías
                </a>
                
            </nav>

            {{-- Botón de Volver al inicio --}}
            <div class="mt-auto pt-4 border-t border-purple-600">
                 <a href="{{ route('inicio') }}" 
                    class="flex items-center justify-center p-3 rounded-lg text-sm bg-purple-600 transition duration-150 ease-in-out hover:bg-purple-500">
                     Volver al Inicio (Usuario)
                 </a>
            </div>
            
        </aside>

        <!-- Contenido Principal -->
        <main class="flex-1 p-8 overflow-y-auto">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
