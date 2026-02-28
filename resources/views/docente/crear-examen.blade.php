@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-primary mb-1">Nuevo Examen</h1>
            <p class="text-gray-600">{{ $curso->nombre }}</p>
        </div>
        <a href="{{ route('docente.examenes', $curso) }}"
            class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8 max-w-4xl mx-auto border-2 border-primary/20">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 border-primary/20">
            <div class="bg-primary/10 p-3 rounded-lg">
                <svg class="w-6 h-6 text-primary" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-primary">Información del Examen</h2>
                <p class="text-sm text-gray-600">Complete los datos del nuevo examen</p>
            </div>
        </div>
        <form method="post" action="{{ route('docente.examenes.guardar', $curso) }}">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Título</label>
                <input type="text" name="titulo" value="{{ old('titulo') }}"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" required>
                @error('titulo')
                    <p class="text-red-600 text-xs mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Descripción</label>
                <textarea name="descripcion" rows="3"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">{{ old('descripcion') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Puntaje Total</label>
                    <input type="number" name="puntaje_total" value="{{ old('puntaje_total', 20) }}" min="1"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" required>
                    @error('puntaje_total')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tiempo Límite (minutos)</label>
                    <input type="number" name="tiempo_limite" value="{{ old('tiempo_limite') }}" min="1"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                        placeholder="Sin límite">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Fecha Inicio</label>
                    <input type="datetime-local" name="fecha_inicio" value="{{ old('fecha_inicio') }}"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" required>
                    @error('fecha_inicio')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Fecha Fin</label>
                    <input type="datetime-local" name="fecha_fin" value="{{ old('fecha_fin') }}"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all" required>
                    @error('fecha_fin')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Intentos Permitidos</label>
                <input type="number" name="intentos_permitidos" value="{{ old('intentos_permitidos', 1) }}" min="1"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
            </div>

            <div class="bg-primary/5 rounded-lg p-5 mb-6">
                <h3 class="font-bold text-primary mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                    </svg>
                    Configuración Adicional
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <label class="flex items-start gap-3 p-3 bg-white rounded-lg cursor-pointer hover:bg-primary/10 transition-all">
                        <input type="checkbox" name="orden_aleatorio_preguntas" value="1"
                            {{ old('orden_aleatorio_preguntas') ? 'checked' : '' }}
                            class="mt-1 w-5 h-5 rounded border-2 border-gray-300 text-primary focus:ring-2 focus:ring-primary/20">
                        <div>
                            <p class="text-sm font-semibold text-gray-700">Orden aleatorio de preguntas</p>
                            <p class="text-xs text-gray-500">Las preguntas aparecerán en orden diferente</p>
                        </div>
                    </label>
                    <label class="flex items-start gap-3 p-3 bg-white rounded-lg cursor-pointer hover:bg-primary/10 transition-all">
                        <input type="checkbox" name="orden_aleatorio_alternativas" value="1"
                            {{ old('orden_aleatorio_alternativas') ? 'checked' : '' }}
                            class="mt-1 w-5 h-5 rounded border-2 border-gray-300 text-primary focus:ring-2 focus:ring-primary/20">
                        <div>
                            <p class="text-sm font-semibold text-gray-700">Orden aleatorio de alternativas</p>
                            <p class="text-xs text-gray-500">Las opciones aparecerán en orden diferente</p>
                        </div>
                    </label>
                    <label class="flex items-start gap-3 p-3 bg-white rounded-lg cursor-pointer hover:bg-primary/10 transition-all">
                        <input type="checkbox" name="mostrar_resultados" value="1"
                            {{ old('mostrar_resultados', true) ? 'checked' : '' }}
                            class="mt-1 w-5 h-5 rounded border-2 border-gray-300 text-primary focus:ring-2 focus:ring-primary/20">
                        <div>
                            <p class="text-sm font-semibold text-gray-700">Mostrar resultados al finalizar</p>
                            <p class="text-xs text-gray-500">El estudiante verá su nota inmediatamente</p>
                        </div>
                    </label>
                    <label class="flex items-start gap-3 p-3 bg-white rounded-lg cursor-pointer hover:bg-primary/10 transition-all">
                        <input type="checkbox" name="permitir_revision" value="1"
                            {{ old('permitir_revision') ? 'checked' : '' }}
                            class="mt-1 w-5 h-5 rounded border-2 border-gray-300 text-primary focus:ring-2 focus:ring-primary/20">
                        <div>
                            <p class="text-sm font-semibold text-gray-700">Permitir revisión después</p>
                            <p class="text-xs text-gray-500">El estudiante puede revisar sus respuestas</p>
                        </div>
                    </label>
                    <label class="flex items-start gap-3 p-3 bg-white rounded-lg cursor-pointer hover:bg-primary/10 transition-all">
                        <input type="checkbox" name="navegacion_libre" value="1"
                            {{ old('navegacion_libre', true) ? 'checked' : '' }}
                            class="mt-1 w-5 h-5 rounded border-2 border-gray-300 text-primary focus:ring-2 focus:ring-primary/20">
                        <div>
                            <p class="text-sm font-semibold text-gray-700">Navegación libre entre preguntas</p>
                            <p class="text-xs text-gray-500">Puede ir y volver entre preguntas</p>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="flex-1 bg-primary text-accent px-6 py-3 rounded-lg font-bold hover:bg-primary/90 transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Crear Examen
                </button>
                <a href="{{ route('docente.examenes', $curso) }}"
                    class="px-6 py-3 border-2 border-gray-300 rounded-lg font-bold text-gray-700 hover:bg-gray-50 transition-all flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
            </div>
        </form>
    </div>
@endsection
