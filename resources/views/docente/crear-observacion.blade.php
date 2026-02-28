@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Nueva Observación - {{ $curso->nombre }}</h1>
        <a href="{{ route('docente.observaciones', $curso) }}" class="text-blue-600 hover:underline text-sm">← Volver</a>
    </div>

    <div class="bg-white rounded border border-gray-200 p-6 max-w-lg">
        <form method="post" action="{{ route('docente.observaciones.guardar', $curso) }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Estudiante</label>
                <select name="estudiante_id" class="w-full p-2 text-sm border border-gray-300 rounded">
                    <option value="">Seleccionar</option>
                    @foreach ($curso->estudiantes as $e)
                        <option value="{{ $e->id }}" {{ old('estudiante_id') == $e->id ? 'selected' : '' }}>
                            {{ $e->nombreCompleto() }}</option>
                    @endforeach
                </select>
                @error('estudiante_id')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Observación</label>
                <textarea name="texto" rows="4"
                    class="w-full p-2 text-sm border border-gray-300 rounded">{{ old('texto') }}</textarea>
                @error('texto')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white px-4 py-2 rounded font-semibold hover:bg-blue-700 text-sm">Registrar
                Observación</button>
        </form>
    </div>
@endsection
