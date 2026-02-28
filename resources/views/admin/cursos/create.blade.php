@extends('layouts.app')
@section('contenido')
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Crear Curso</h1>

    <form method="post" action="{{ route('admin.cursos.guardar') }}"
        class="bg-white p-6 rounded border border-gray-200 max-w-lg">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre del Curso</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}"
                class="w-full p-2 text-sm border border-gray-300 rounded">
            @error('nombre')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Descripción</label>
            <textarea name="descripcion" rows="3" class="w-full p-2 text-sm border border-gray-300 rounded">{{ old('descripcion') }}</textarea>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Periodo</label>
            <select name="periodo_id" class="w-full p-2 text-sm border border-gray-300 rounded">
                <option value="">Seleccionar periodo</option>
                @foreach ($periodos as $periodo)
                    <option value="{{ $periodo->id }}" {{ old('periodo_id') == $periodo->id ? 'selected' : '' }}>
                        {{ $periodo->nombre }}</option>
                @endforeach
            </select>
            @error('periodo_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Docente (opcional)</label>
            <select name="docente_id" class="w-full p-2 text-sm border border-gray-300 rounded">
                <option value="">Sin asignar</option>
                @foreach ($docentes as $docente)
                    <option value="{{ $docente->id }}">{{ $docente->nombreCompleto() }} - {{ $docente->dni }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-3">
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded font-semibold hover:bg-blue-700">Guardar</button>
            <a href="{{ route('admin.cursos') }}"
                class="bg-gray-500 text-white px-6 py-2 rounded font-semibold hover:bg-gray-600">Cancelar</a>
        </div>
    </form>
@endsection
