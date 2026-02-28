@extends('layouts.app')
@section('contenido')
    <div class="w-full flex justify-center items-center mt-20">
        <form method="post" action="{{ route('password.update') }}"
            class="p-6 border border-gray-300 rounded-lg shadow-lg bg-white w-full max-w-md">
            <h2 class="text-2xl text-gray-700 font-bold text-center">Restablecer Contraseña</h2>
            <hr class="my-4 border-gray-300">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="p-3 flex flex-col">
                <label class="text-sm text-gray-700 font-semibold mb-1" for="email">Correo Electrónico</label>
                <input class="p-2 text-sm border rounded border-gray-400" type="email" name="email"
                    value="{{ old('email') }}" id="email">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="p-3 flex flex-col">
                <label class="text-sm text-gray-700 font-semibold mb-1" for="password">Nueva Contraseña</label>
                <input class="p-2 text-sm border border-gray-400 rounded" type="password" name="password" id="password">
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
                <input type="submit" value="Restablecer Contraseña"
                    class="w-full bg-blue-600 text-white p-2 rounded font-semibold cursor-pointer hover:bg-blue-700">
            </div>
        </form>
    </div>
@endsection
