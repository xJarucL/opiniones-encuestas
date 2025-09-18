@extends('components.menu')

@section('title', 'Inicia sesión')

@section('content')
    <div class="bg-white p-8 rounded-2xl shadow-lg text-center">
        <h1 class="text-4xl font-bold text-blue-600 mb-4">
            ¡Inicia sesión!
        </h1>
        <form action="">
            <label for="">Correo electrónico</label>
            <input type="email" name="email">

            <label for="">Contraseña</label>
            <input type="password" name="password">

            <input type="button" value="Entrar">
        </form>
    </div>
@endsection
