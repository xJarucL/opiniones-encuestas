@extends('components.menu') {{-- <--- ¡ESTA ES LA LÍNEA CORRECTA! --}}@section('title', isset($usuario) ? 'Editar Usuario' : 'Registrar Usuario')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        
        <!-- Header del formulario -->
        <div class="bg-gradient-to-r from-purple-600 to-purple-800 px-8 py-6">
            <h1 class="text-3xl font-bold text-white">
                {{ isset($usuario) ? 'Editar Usuario' : 'Registrar Nuevo Usuario' }}
            </h1>
            <p class="text-purple-100 mt-2">
                {{ isset($usuario) ? 'Actualiza la información del usuario' : 'Completa el formulario para crear un nuevo usuario' }}
            </p>
        </div>

        <!-- Formulario -->
        <form id="formUsuario" 
              {{-- ¡CAMBIO AQUÍ! Se usa el nombre de ruta correcto: 'usuarios.guardar' --}}
              action="{{ isset($usuario) ? route('usuarios.update', $usuario->pk_usuario) : route('usuarios.guardar') }}" 
              method="POST" 
              enctype="multipart/form-data" 
              class="p-8 space-y-6">
            @csrf
            @if(isset($usuario))
                @method('PUT')
                <input type="hidden" name="id" value="{{ $usuario->pk_usuario }}">
            @endif

            <!-- Vista previa de imagen -->
            <div class="flex justify-center mb-6">
                <div class="relative">
                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 p-1 shadow-xl">
                        <img id="preview" 
                             src="{{ isset($usuario) && $usuario->img_user ? asset('storage/'.$usuario->img_user) : asset('img/default.webp') }}" 
                             alt="Preview" 
                             class="w-full h-full object-cover rounded-full border-4 border-white">
                    </div>
                    <label for="img_user" 
                           class="absolute bottom-0 right-0 bg-purple-600 text-white p-2 rounded-full cursor-pointer hover:bg-purple-700 transition shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </label>
                    <input type="file" 
                           id="img_user" 
                           name="img_user" 
                           accept="image/*" 
                           class="hidden" 
                           onchange="previewImage(event)">
                </div>
            </div>

            <!-- Grid de campos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nombre de Usuario <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           value="{{ old('username', $usuario->username ?? '') }}" 
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @error('username')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        Correo Electrónico <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $usuario->email ?? '') }}" 
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nombres -->
                <div>
                    <label for="nombres" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nombre(s) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nombres" 
                           name="nombres" 
                           value="{{ old('nombres', $usuario->nombres ?? '') }}" 
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @error('nombres')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Apellido Paterno -->
                <div>
                    <label for="ap_paterno" class="block text-sm font-semibold text-gray-700 mb-2">
                        Apellido Paterno <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="ap_paterno" 
                           name="ap_paterno" 
                           value="{{ old('ap_paterno', $usuario->ap_paterno ?? '') }}" 
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @error('ap_paterno')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Apellido Materno -->
                <div>
                    <label for="ap_materno" class="block text-sm font-semibold text-gray-700 mb-2">
                        Apellido Materno
                    </label>
                    <input type="text" 
                           id="ap_materno" 
                           name="ap_materno" 
                           value="{{ old('ap_materno', $usuario->ap_materno ?? '') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @error('ap_materno')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        Contraseña {{ isset($usuario) ? '(dejar en blanco para no cambiar)' : '' }} <span class="text-red-500">{{ isset($usuario) ? '' : '*' }}</span>
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           {{ isset($usuario) ? '' : 'required' }}
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('usuarios.lista') }}" 
                   class="px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-semibold rounded-lg hover:from-purple-700 hover:to-purple-800 shadow-lg hover:shadow-xl transition-all duration-200">
                    {{ isset($usuario) ? 'Actualizar Usuario' : 'Registrar Usuario' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
