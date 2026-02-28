@extends('layouts.app')
@section('contenido')
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Crear Periodo Académico</h1>

    <form method="post" action="{{ route('admin.periodos.guardar') }}"
        class="bg-white p-6 rounded border border-gray-200 max-w-lg">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}"
                class="w-full p-2 text-sm border border-gray-300 rounded" placeholder="Ej: 2026-I">
            @error('nombre')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}"
                    class="w-full p-2 text-sm border border-gray-300 rounded">
                @error('fecha_inicio')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha Fin</label>
                <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}"
                    class="w-full p-2 text-sm border border-gray-300 rounded">
                @error('fecha_fin')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Estado</label>
            <select name="estado" class="w-full p-2 text-sm border border-gray-300 rounded">
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
            </select>
        </div>
        <div class="flex gap-3">
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded font-semibold hover:bg-blue-700">Guardar</button>
            <a href="{{ route('admin.periodos') }}"
                class="bg-gray-500 text-white px-6 py-2 rounded font-semibold hover:bg-gray-600">Cancelar</a>
        </div>
    </form>
@endsection
