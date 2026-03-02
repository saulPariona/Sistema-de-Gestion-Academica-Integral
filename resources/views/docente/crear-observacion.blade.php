@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-primary mb-1">Nueva Observación</h1>
            <em class="text-gray-600 text-sm">{{ $curso->nombre }}</em>
        </div>
        <a href="{{ route('docente.observaciones', $curso) }}"
            class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver
        </a>
    </div>

    <div class="bg-white rounded-sm shadow-lg p-8 max-w-2xl mx-auto border-2 border-gray-400">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 border-gray-200">
            <div class="bg-primary/10 p-3 rounded-sm">
                <svg class="w-6 h-6 text-primary" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-primary">Registrar Observación</h2>
                <p class="text-sm text-gray-600">Complete los datos de la observación</p>
            </div>
        </div>

        <form method="post" action="{{ route('docente.observaciones.guardar', $curso) }}">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Estudiante</label>
                <select name="estudiante_id" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm">
                    <option value="">Seleccionar estudiante</option>
                    @foreach ($curso->estudiantes as $e)
                        <option value="{{ $e->id }}" {{ old('estudiante_id') == $e->id ? 'selected' : '' }}>
                            {{ $e->nombreCompleto() }}</option>
                    @endforeach
                </select>
                @error('estudiante_id')
                    <p class="text-red-600 text-xs mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Observación</label>
                <textarea name="texto" rows="4" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm">{{ old('texto') }}</textarea>
                @error('texto')
                    <p class="text-red-600 text-xs mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="flex gap-3 justify-center">
                <button type="submit"
                    class="bg-primary text-accent px-6 py-3 rounded-sm font-bold hover:bg-primary/90 transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Registrar Observación
                </button>
            </div>
        </form>
    </div>
@endsection
