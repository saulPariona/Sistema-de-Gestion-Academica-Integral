@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Agregar Apoderado - {{ $estudiante->nombreCompleto() }}</h1>
        <a href="{{ route('admin.apoderados', $estudiante) }}" class="text-blue-600 hover:underline text-sm">← Volver</a>
    </div>

    <div class="bg-white rounded border border-gray-200 p-6 max-w-lg">
        <form method="post" action="{{ route('admin.apoderados.guardar', $estudiante) }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre Completo</label>
                <input type="text" name="nombre_completo" value="{{ old('nombre_completo') }}"
                    class="w-full p-2 text-sm border border-gray-300 rounded">
                @error('nombre_completo')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">DNI</label>
                <input type="text" name="dni" value="{{ old('dni') }}" maxlength="8"
                    class="w-full p-2 text-sm border border-gray-300 rounded">
                @error('dni')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Teléfono</label>
                <input type="text" name="telefono" value="{{ old('telefono') }}"
                    class="w-full p-2 text-sm border border-gray-300 rounded">
                @error('telefono')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full p-2 text-sm border border-gray-300 rounded">
                @error('email')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Parentesco</label>
                <select name="parentesco" class="w-full p-2 text-sm border border-gray-300 rounded">
                    <option value="">Seleccionar</option>
                    @foreach (['padre', 'madre', 'tutor', 'otro'] as $p)
                        <option value="{{ $p }}" {{ old('parentesco') == $p ? 'selected' : '' }}>
                            {{ ucfirst($p) }}</option>
                    @endforeach
                </select>
                @error('parentesco')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white px-4 py-2 rounded font-semibold hover:bg-blue-700 text-sm">Guardar
                Apoderado</button>
        </form>
    </div>
@endsection
