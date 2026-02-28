@extends('layouts.app')
@section('contenido')
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Editar Curso</h1>

    <form method="post" action="{{ route('admin.cursos.actualizar', $curso->id) }}"
        class="bg-white p-6 rounded border border-gray-200 max-w-lg">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre del Curso</label>
            <input type="text" name="nombre" value="{{ old('nombre', $curso->nombre) }}"
                class="w-full p-2 text-sm border border-gray-300 rounded">
            @error('nombre')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Descripción</label>
            <textarea name="descripcion" rows="3" class="w-full p-2 text-sm border border-gray-300 rounded">{{ old('descripcion', $curso->descripcion) }}</textarea>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Periodo</label>
            <select name="periodo_id" class="w-full p-2 text-sm border border-gray-300 rounded">
                @foreach ($periodos as $periodo)
                    <option value="{{ $periodo->id }}"
                        {{ old('periodo_id', $curso->periodo_id) == $periodo->id ? 'selected' : '' }}>
                        {{ $periodo->nombre }}</option>
                @endforeach
            </select>
            @error('periodo_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Docentes</label>
            <select name="docente_id[]" multiple class="w-full p-2 text-sm border border-gray-300 rounded" size="5">
                @foreach ($docentes as $docente)
                    <option value="{{ $docente->id }}"
                        {{ $curso->docentes->contains($docente->id) ? 'selected' : '' }}>
                        {{ $docente->nombreCompleto() }}</option>
                @endforeach
            </select>
            <p class="text-xs text-gray-500 mt-1">Mantén Ctrl para seleccionar varios.</p>
        </div>
        <div class="flex gap-3">
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded font-semibold hover:bg-blue-700">Actualizar</button>
            <a href="{{ route('admin.cursos') }}"
                class="bg-gray-500 text-white px-6 py-2 rounded font-semibold hover:bg-gray-600">Cancelar</a>
        </div>
    </form>
@endsection
