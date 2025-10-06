<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Opiniones y Encuestas')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/funciones.js', 'resources/js/sweetalert.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">

    <nav class="bg-purple-600 text-white p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold">Encuestas y Opiniones</h1>

        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center focus:outline-none transition">
                <img
                    src="{{ auth()->user()->img_user ? asset('storage/' . auth()->user()->img_user) : asset('img/default.webp') }}"
                    alt="Usuario"
                    class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-md hover:ring-2 hover:ring-white transition"
                />
            </button>

            <div
                x-show="open"
                @click.away="open = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-2"
                class="absolute right-0 mt-2 w-44 bg-white/90 backdrop-blur-md text-gray-800 rounded-xl shadow-xl py-2 z-50 border-0"
                style="display: none;"
            >
                <div class="absolute top-0 right-4 -mt-2 w-3 h-3 bg-white/90 backdrop-blur-md rotate-45 shadow-md"></div>

                <a href="{{ route('perfil') }}"
                   class="block px-4 py-2 hover:bg-purple-100 hover:text-purple-700 rounded-lg transition">
                    Perfil
                </a>
                <form id="logout-form" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            data-swal-form
                            data-target-form="logout-form"
                            data-swal-title="Cerrar sesión"
                            data-swal-text="¿Deseas cerrar tu sesión actualmente activa?"
                            data-swal-icon="warning"
                            data-swal-confirm="Sí, cerrar sesión"
                            data-swal-cancel="Cancelar"
                            class="w-full text-left px-4 py-2 hover:bg-red-100 hover:text-red-700 rounded-lg transition">
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="p-6">
        @yield('content')
    </main>

    @stack('scripts')

</body>
</html>
