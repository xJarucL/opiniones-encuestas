@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/funciones.js'])
<div class="mt-6 ml-6">

    <x-msj-alert />

    <form id="form-insertar" data-url="{{ route('iniciando') }}" class="space-y-4">
        @csrf

        <h1 class="text-base font-medium">Iniciar sesión</h1>

        <div>
            <label for="email" class="block mb-1">Correo electrónico</label>
            <input type="text" name="email" id="email" required class="border px-2 py-1 w-64">
        </div>

        <div>
            <label for="password" class="block mb-1">Contraseña</label>
            <input type="password" name="password" id="password" required class="border px-2 py-1 w-64">
        </div>

        <div>
            <input type="submit" value="Ingresar"
                class="bg-blue-600 text-white font-semibold px-6 py-2 rounded-md shadow-md
                    hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400
                    transition duration-200 cursor-pointer" />
        </div>

    </form>
</div>
