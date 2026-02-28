@extends('layouts.app')
@section('contenido')
    <div class="w-full flex justify-center items-center mt-20">
        <form method="post" action="{{ route('password.email') }}"
            class="p-6 border border-gray-300 rounded-lg shadow-lg bg-white w-full max-w-md">
            <h2 class="text-2xl text-gray-700 font-bold text-center">Recuperar Contraseña</h2>
            <hr class="my-4 border-gray-300">
            @csrf
            <div class="p-3 flex flex-col">
                <label class="text-sm text-gray-700 font-semibold mb-1" for="email">Correo Electrónico</label>
                <input class="p-2 text-sm border rounded border-gray-400 focus:border-blue-500 focus:outline-none"
                    type="email" value="{{ old('email') }}" name="email" id="email"
                    placeholder="correo@ejemplo.com">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="text-center mt-2">
                <input type="submit" value="Enviar enlace de recuperación"
                    class="w-full bg-blue-600 text-white p-2 rounded font-semibold cursor-pointer hover:bg-blue-700">
            </div>
            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">Volver al inicio de sesión</a>
            </div>
        </form>
    </div>
@endsection
