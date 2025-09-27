@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/funciones.js', 'resources/js/sweetalerts.js'])
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="min-h-screen flex flex-col items-center justify-center bg-purple-800 p-6 space-y-6">

    <div class="text-center">
        <h1 class="text-4xl font-bold text-white">Encuestas y Opiniones</h1>
        <p class="text-purple-200 mt-2">Ingresa tu nueva contraseña para continuar</p>
    </div>

    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8 animate-fade-slide hover:shadow-2xl transition-shadow duration-300">

        <x-msj-alert />

        <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Restablecer Contraseña</h2>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nueva contraseña</label>
                <input type="password" id="password" name="password" placeholder="••••••••••" required
                    class="w-full border border-gray-300 px-3 py-2 rounded-lg shadow-sm
                           focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-purple-600">
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar nueva contraseña</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••••" required
                    class="w-full border border-gray-300 px-3 py-2 rounded-lg shadow-sm
                           focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-purple-600">
            </div>

            <div>
                <input type="submit" value="Restablecer Contraseña"
                    class="w-full bg-purple-600 text-white font-semibold px-6 py-2 rounded-lg shadow-md
                           hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500
                           transition duration-200 cursor-pointer" />
            </div>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:underline">
                Volver al inicio de sesión
            </a>
        </div>
    </div>
</div>

<style>
@keyframes fadeSlide {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
.animate-fade-slide {
  animation: fadeSlide 0.8s ease-out;
}
</style>
