@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-primary mb-1">Crear Curso</h1>
            <p class="text-gray-600">Registra un nuevo curso en el sistema</p>
        </div>
        <a href="{{ route('admin.cursos') }}"
            class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Cursos
        </a>
    </div>

    <div class="bg-white rounded-sm shadow-lg border-2 p-4 max-w-2xl mx-auto">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 border-primary/20">
            <div class="bg-primary/10 p-3 rounded-sm">
                <svg class="w-6 h-6 text-primary" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-primary">Datos del Curso</h2>
                <p class="text-sm text-gray-500">Información y asignación</p>
            </div>
        </div>

        <form method="post" action="{{ route('admin.cursos.guardar') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre del Curso</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}"
                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm"
                        placeholder="Ej: Matemáticas Avanzadas">
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
                    <textarea name="descripcion" rows="3" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm"
                        placeholder="Descripción del curso...">{{ old('descripcion') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Periodo</label>
                    <select name="periodo_id" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm">
                        <option value="">Seleccionar periodo</option>
                        @foreach ($periodos as $periodo)
                            <option value="{{ $periodo->id }}" {{ old('periodo_id') == $periodo->id ? 'selected' : '' }}>
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
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Docente
                        <span class="text-xs text-gray-500 font-normal">(opcional)</span>
                    </label>
                    <select name="docente_id" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm">
                        <option value="">Sin asignar</option>
                        @foreach ($docentes as $docente)
                            <option value="{{ $docente->id }}">{{ $docente->nombreCompleto() }} - {{ $docente->dni }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-6 flex justify-center">
                <button type="submit" class="flex items-center gap-2 bg-primary text-accent px-6 py-3 rounded-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Guardar Curso
                </button>
            </div>
        </form>
    </div>
@endsection
