@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/funciones.js', 'resources/js/sweetalerts.js'])
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<div class="min-h-screen flex flex-col items-center justify-center bg-purple-800 p-6 space-y-6">

    <div class="text-center">
        <h1 class="text-4xl font-bold text-white">Encuestas y Opiniones</h1>
        <p class="text-purple-200 mt-2">Recupera tu contraseña para continuar participando</p>
    </div>

    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8 animate-fade-slide hover:shadow-2xl transition-shadow duration-300">

        <x-msj-alert />

        <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Recuperar Contraseña</h2>

        <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                <input type="email" name="email" id="email" placeholder="ejemplo@gmail.com" required
                    class="w-full border border-gray-300 px-3 py-2 rounded-lg shadow-sm
                           focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-purple-600">
            </div>

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex justify-center">
                <div class="g-recaptcha" data-sitekey="6Lf9ltYrAAAAAEVLBf6GEyIIE6pQTXa61RELzjIh"></div>
            </div>

            <div>
                <input type="submit" value="Enviar instrucciones"
                    class="w-full bg-purple-600 text-white font-semibold px-6 py-2 rounded-lg shadow-md
                           hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500
                           transition duration-200 cursor-pointer" />
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:underline">
                    ¿Recordaste tu contraseña? Inicia sesión
                </a>
            </div>
        </form>
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
