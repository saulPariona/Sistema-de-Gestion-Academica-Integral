@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-primary mb-1">Crear Periodo Académico</h1>
            <p class="text-gray-600">Registra un nuevo periodo escolar</p>
        </div>
        <a href="{{ route('admin.periodos') }}"
            class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Periodos
        </a>
    </div>

    <div class="bg-white rounded-sm shadow-lg border-2 p-6 max-w-2xl mx-auto">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b-2">
            <div class="bg-primary/10 p-3 rounded-sm">
                <svg class="w-6 h-6 text-primary" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-primary">Datos del Periodo</h2>
                <p class="text-sm text-gray-500">Configura las fechas y estado</p>
            </div>
        </div>

        <form method="post" action="{{ route('admin.periodos.guardar') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}"
                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm" placeholder="Ej: 2026-I">
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

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha Inicio</label>
                        <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}"
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm">
                        @error('fecha_inicio')
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
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha Fin</label>
                        <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}"
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm">
                        @error('fecha_fin')
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
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Estado</label>
                    <select name="estado" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex justify-center gap-3">
                <button type="submit" class="flex items-center gap-2 bg-primary text-accent px-6 py-3 rounded-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Guardar Periodo
                </button>
            </div>
        </form>
    </div>
@endsection
