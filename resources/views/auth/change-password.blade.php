@extends('layouts.app')
@section('contenido')
    <div class="w-full flex justify-center items-center mt-10">
        <form method="post" action="{{ url('/change-password') }}"
            class="p-6 border border-gray-300 rounded-lg shadow-lg bg-white w-full max-w-md">
            <h2 class="text-2xl text-gray-700 font-bold text-center">Cambiar Contraseña</h2>
            <hr class="my-4 border-gray-300">
            @csrf
            <div class="p-3 flex flex-col">
                <label class="text-sm text-gray-700 font-semibold mb-1" for="current_password">Contraseña Actual</label>
                <input class="p-2 text-sm border border-gray-400 rounded" type="password" name="current_password"
                    id="current_password">
                @error('current_password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="p-3 flex flex-col">
                <label class="text-sm text-gray-700 font-semibold mb-1" for="password">Nueva Contraseña</label>
                <input class="p-2 text-sm border border-gray-400 rounded" type="password" name="password" id="password">
                <p class="text-xs text-gray-500 mt-1">Mínimo 8 caracteres, mayúsculas, minúsculas y números.</p>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="p-3 flex flex-col">
                <label class="text-sm text-gray-700 font-semibold mb-1" for="password_confirmation">Confirmar
                    Contraseña</label>
                <input class="p-2 text-sm border border-gray-400 rounded" type="password" name="password_confirmation"
                    id="password_confirmation">
            </div>
            <div class="text-center mt-2">
                <input type="submit" value="Cambiar Contraseña"
                    class="w-full bg-blue-600 text-white p-2 rounded font-semibold cursor-pointer hover:bg-blue-700">
            </div>
        </form>
    </div>
@endsection
