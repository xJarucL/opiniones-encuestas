@extends('components.menu')

@section('title', isset($usuario) ? 'Editar usuario' : 'Registrar usuario')

@section('content')
<div class="max-w-3xl mx-auto mt-10 p-8 bg-white rounded-xl shadow-lg space-y-6">

    <x-msj-alert />

    <form id="form-insertar"
          data-url="{{ isset($usuario) ? route('usuarios.update', $usuario->pk_usuario) : route('guardar.user') }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-6">
        @csrf
        @if(isset($usuario))
            @method('PUT')
        @endif

        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-purple-700">
                {{ isset($usuario) ? 'Editar usuario' : 'Registrar usuario' }}
            </h1>

            <a href="{{ url()->previous() }}"
                title="Volver"
                class="inline-flex items-center space-x-2 bg-gray-200 text-gray-700 px-3 py-2 rounded-full shadow hover:bg-gray-300 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                <span>Volver</span>
            </a>
        </div>

        <div>
            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Nombre de usuario *</label>
            <input type="text" name="username" id="username"
                   value="{{ old('username', $usuario->username ?? '') }}"
                   required
                   class="w-full border border-gray-300 rounded-md px-4 py-2 shadow-sm focus:ring-purple-500 focus:border-purple-500 outline-none" />
        </div>

        <div>
            <label for="nombres" class="block text-sm font-medium text-gray-700 mb-1">Nombres *</label>
            <input type="text" name="nombres" id="nombres"
                   value="{{ old('nombres', $usuario->nombres ?? '') }}"
                   required
                   class="w-full border border-gray-300 rounded-md px-4 py-2 shadow-sm focus:ring-purple-500 focus:border-purple-500 outline-none" />
        </div>

        <div>
            <label for="ap_paterno" class="block text-sm font-medium text-gray-700 mb-1">Apellido paterno *</label>
            <input type="text" name="ap_paterno" id="ap_paterno"
                   value="{{ old('ap_paterno', $usuario->ap_paterno ?? '') }}"
                   required
                   class="w-full border border-gray-300 rounded-md px-4 py-2 shadow-sm focus:ring-purple-500 focus:border-purple-500 outline-none" />
        </div>

        <div>
            <label for="ap_materno" class="block text-sm font-medium text-gray-700 mb-1">Apellido materno</label>
            <input type="text" name="ap_materno" id="ap_materno"
                   value="{{ old('ap_materno', $usuario->ap_materno ?? '') }}"
                   class="w-full border border-gray-300 rounded-md px-4 py-2 shadow-sm focus:ring-purple-500 focus:border-purple-500 outline-none" />
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico *</label>
            <input type="email" name="email" id="email"
                   value="{{ old('email', $usuario->email ?? '') }}"
                   required
                   class="w-full border border-gray-300 rounded-md px-4 py-2 shadow-sm focus:ring-purple-500 focus:border-purple-500 outline-none" />
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                {{ isset($usuario) ? 'Nueva contraseña (opcional)' : 'Contraseña inicial *' }}
            </label>
            <input type="password" name="password" id="password"
                   {{ isset($usuario) ? '' : 'required' }}
                   class="w-full border border-gray-300 rounded-md px-4 py-2 shadow-sm focus:ring-purple-500 focus:border-purple-500 outline-none" />
        </div>

        <div class="flex flex-col items-center">
            <label class="block text-sm font-medium text-gray-700 mb-2">Imagen de usuario</label>

            <div id="img-container" class="relative w-32 h-32 mb-2 cursor-pointer">
                <img id="preview-img"
                     src="{{ isset($usuario) && $usuario->img_user ? asset('storage/'.$usuario->img_user) : 'https://via.placeholder.com/150' }}"
                     alt=""
                     class="w-32 h-32 object-cover rounded-full border-4 border-purple-500 shadow-lg transition-transform duration-300 hover:scale-105" />
                <div class="absolute inset-0 rounded-full bg-purple-600 bg-opacity-30 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300">
                    <span class="text-white font-semibold text-sm">Cambiar</span>
                </div>
            </div>

            <input type="file" name="img_user" id="img_user" accept="image/*" class="hidden" />
        </div>

        @if(isset($usuario))
            <input type="hidden" name="id" value="{{ $usuario->pk_usuario }}">
        @endif

        <div>
            <input type="submit"
                   value="{{ isset($usuario) ? 'Actualizar' : 'Registrar' }}"
                   class="w-full bg-purple-600 text-white font-semibold px-6 py-3 rounded-md shadow-md
                          hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-400
                          transition duration-200 cursor-pointer" />
        </div>
    </form>
</div>

<script>
    document.getElementById('img_user').addEventListener('change', function(e) {
        const [file] = e.target.files;
        if (file) {
            document.getElementById('preview-img').src = URL.createObjectURL(file);
        }
    });

    document.getElementById('img-container').addEventListener('click', function() {
        document.getElementById('img_user').click();
    });
</script>
@endsection
