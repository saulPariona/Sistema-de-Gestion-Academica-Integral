@extends('layouts.app')
@section('contenido')
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Editar Periodo Académico</h1>

    <form method="post" action="{{ route('admin.periodos.actualizar', $periodo->id) }}"
        class="bg-white p-6 rounded border border-gray-200 max-w-lg">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre</label>
            <input type="text" name="nombre" value="{{ old('nombre', $periodo->nombre) }}"
                class="w-full p-2 text-sm border border-gray-300 rounded">
            @error('nombre')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha Inicio</label>
                <input type="date" name="fecha_inicio"
                    value="{{ old('fecha_inicio', $periodo->fecha_inicio->format('Y-m-d')) }}"
                    class="w-full p-2 text-sm border border-gray-300 rounded">
                @error('fecha_inicio')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha Fin</label>
                <input type="date" name="fecha_fin"
                    value="{{ old('fecha_fin', $periodo->fecha_fin->format('Y-m-d')) }}"
                    class="w-full p-2 text-sm border border-gray-300 rounded">
                @error('fecha_fin')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Estado</label>
            <select name="estado" class="w-full p-2 text-sm border border-gray-300 rounded">
                <option value="activo" {{ $periodo->estado == 'activo' ? 'selected' : '' }}>Activo</option>
                <option value="inactivo" {{ $periodo->estado == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>
        <div class="flex gap-3">
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded font-semibold hover:bg-blue-700">Actualizar</button>
            <a href="{{ route('admin.periodos') }}"
                class="bg-gray-500 text-white px-6 py-2 rounded font-semibold hover:bg-gray-600">Cancelar</a>
        </div>
    </form>
@endsection
