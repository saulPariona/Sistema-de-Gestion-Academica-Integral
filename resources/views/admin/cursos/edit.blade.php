@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-primary mb-1">Editar Curso</h1>
            <p class="text-gray-600">{{ $curso->nombre }}</p>
        </div>
        <a href="{{ route('admin.cursos') }}"
            class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Cursos
        </a>
    </div>

    <div class="bg-white rounded-sm shadow-lg border-2  p-6 max-w-2xl mx-auto">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 ">
            <div class="bg-primary/10 p-3 rounded-lg">
                <svg class="w-6 h-6 text-primary" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-primary">Datos del Curso</h2>
                <p class="text-sm text-gray-500">Modifica la información del curso</p>
            </div>
        </div>

        <form method="post" action="{{ route('admin.cursos.actualizar', $curso->id) }}">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre del Curso</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $curso->nombre) }}"
                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm">
                    @error('nombre')
                        <p class="text-red-600 text-xs mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción</label>
                    <textarea name="descripcion" rows="3" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm">{{ old('descripcion', $curso->descripcion) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Periodo</label>
                    <select name="periodo_id" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm">
                        @foreach ($periodos as $periodo)
                            <option value="{{ $periodo->id }}"
                                {{ old('periodo_id', $curso->periodo_id) == $periodo->id ? 'selected' : '' }}>
                                {{ $periodo->nombre }}</option>
                        @endforeach
                    </select>
                    @error('periodo_id')
                        <p class="text-red-600 text-xs mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Docentes asignados</label>
                    {{-- Docentes actuales --}}
                    <div class="mb-4 p-4 bg-primary/5 rounded-sm border-2 border-gray-300">
                        <p class="text-sm font-semibold text-gray-700 mb-2">Docentes actuales:</p>
                        <div class="flex flex-wrap gap-2">
                            @forelse($curso->docentes as $d)
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-300">
                                    <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $d->nombreCompleto() }}
                                </span>
                            @empty
                                <span class="text-sm text-gray-400 italic">Ninguno asignado</span>
                            @endforelse
                        </div>
                    </div>

                    {{-- Selección de docentes --}}
                    <p class="text-sm font-semibold text-gray-700 mb-2">Seleccionar docentes:</p>
                    <div class="space-y-2 max-h-48 overflow-y-auto border-2 border-gray-200 rounded-sm p-3">
                        @foreach ($docentes as $docente)
                            <label
                                class="flex items-center gap-3 p-2 rounded-sm hover:bg-primary/5 cursor-pointer transition-all">
                                <input type="checkbox" name="docente_id[]" value="{{ $docente->id }}"
                                    {{ $curso->docentes->contains($docente->id) ? 'checked' : '' }}
                                    class="w-4 h-4 text-primary border-2 border-gray-300 rounded focus:ring-primary">
                                <div>
                                    <span class="text-sm font-medium text-gray-800">{{ $docente->nombreCompleto() }}</span>
                                    @if ($docente->especialidad)
                                        <span class="text-xs text-gray-500 ml-1">— {{ $docente->especialidad }}</span>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-1.5">Marca los docentes que deseas asignar al curso.</p>
                </div>
            </div>

            <div class="mt-6 flex justify-center gap-3">
                <button type="submit"
                    class="flex items-center gap-2 bg-primary text-accent px-6 py-3 rounded-sm font-bold hover:bg-primary/90 transition-all shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Actualizar Curso
                </button>
            </div>
        </form>
    </div>
@endsection
