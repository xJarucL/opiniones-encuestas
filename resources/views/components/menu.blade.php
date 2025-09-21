<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Opiniones y Encuestas')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/funciones.js'])
</head>
<body class="bg-gray-50">

    <nav class="bg-blue-600 text-white p-4">
        <h1 class="text-xl font-bold">Menú principal de prueba</h1>
    </nav>

    <main class="p-6">
        @yield('content') {{-- 👈 aquí se insertará el contenido de cada vista --}}
    </main>

</body>
</html>
