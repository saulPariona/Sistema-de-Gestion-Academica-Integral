@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-primary mb-1">Editar Examen</h1>
            <em class="text-gray-600 text-sm">{{ $curso->nombre }}</em>
        </div>
        <a href="{{ route('docente.examenes', $curso) }}"
            class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver
        </a>
    </div>

    <div class="bg-white rounded-sm shadow-lg p-8 max-w-4xl mx-auto border-2 border-gray-400">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 border-gray-200">
            <div class="bg-primary/10 p-3 rounded-sm">
                <svg class="w-6 h-6 text-primary" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-primary">Modificar Información del Examen</h2>
                <p class="text-sm text-gray-600">Actualiza los datos del examen: {{ $examen->titulo }}</p>
            </div>
        </div>
        <form method="post" action="{{ route('docente.examenes.actualizar', [$curso, $examen]) }}">
            @csrf
            @method('PUT')

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Título</label>
                <input type="text" name="titulo" value="{{ old('titulo', $examen->titulo) }}"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-sm" required>
                @error('titulo')
                    <p class="text-red-600 text-xs mt-1 flex items-center gap-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Descripción</label>
                <textarea name="descripcion" rows="3" class="w-full px-4 py-3 border-2 border-gray-200 rounded-sm">{{ old('descripcion', $examen->descripcion) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Puntaje Total</label>
                    <input type="number" name="puntaje_total" value="{{ old('puntaje_total', $examen->puntaje_total) }}"
                        min="1" class="w-full px-4 py-3 border-2 border-gray-200 rounded-sm" required>
                    @error('puntaje_total')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tiempo Límite (minutos)</label>
                    <input type="number" name="tiempo_limite" value="{{ old('tiempo_limite', $examen->tiempo_limite) }}"
                        min="1" class="w-full px-4 py-3 border-2 border-gray-200 rounded-sm"
                        placeholder="Sin límite">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Fecha Inicio</label>
                    <input type="datetime-local" name="fecha_inicio"
                        value="{{ old('fecha_inicio', $examen->fecha_inicio ? $examen->fecha_inicio->format('Y-m-d\TH:i') : '') }}"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-sm" required>
                    @error('fecha_inicio')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Fecha Fin</label>
                    <input type="datetime-local" name="fecha_fin"
                        value="{{ old('fecha_fin', $examen->fecha_fin ? $examen->fecha_fin->format('Y-m-d\TH:i') : '') }}"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-sm" required>
                    @error('fecha_fin')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Intentos Permitidos</label>
                <input type="number" name="intentos_permitidos"
                    value="{{ old('intentos_permitidos', $examen->intentos_permitidos) }}" min="1"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-sm">
            </div>

            <div class="bg-primary/5 rounded-sm p-5 mb-6">
                <h3 class="font-bold text-primary mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                            clip-rule="evenodd" />
                    </svg>
                    Configuración Adicional
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <label class="flex items-start gap-3 p-3 bg-white rounded-sm cursor-pointer transition-all">
                        <input type="checkbox" name="orden_aleatorio_preguntas" value="1"
                            {{ old('orden_aleatorio_preguntas', $examen->orden_aleatorio_preguntas) ? 'checked' : '' }}
                            class="mt-1 w-5 h-5 rounded border-2 border-gray-300 text-primary">
                        <div>
                            <p class="text-sm font-semibold text-gray-700">Orden aleatorio de preguntas</p>
                            <p class="text-xs text-gray-500">Las preguntas aparecerán en orden diferente</p>
                        </div>
                    </label>
                    <label class="flex items-start gap-3 p-3 bg-white rounded-sm cursor-pointer transition-all">
                        <input type="checkbox" name="orden_aleatorio_alternativas" value="1"
                            {{ old('orden_aleatorio_alternativas', $examen->orden_aleatorio_alternativas) ? 'checked' : '' }}
                            class="mt-1 w-5 h-5 rounded border-2 border-gray-300 text-primary">
                        <div>
                            <p class="text-sm font-semibold text-gray-700">Orden aleatorio de alternativas</p>
                            <p class="text-xs text-gray-500">Las opciones aparecerán en orden diferente</p>
                        </div>
                    </label>
                    <label class="flex items-start gap-3 p-3 bg-white rounded-sm cursor-pointer transition-all">
                        <input type="checkbox" name="mostrar_resultados" value="1"
                            {{ old('mostrar_resultados', $examen->mostrar_resultados) ? 'checked' : '' }}
                            class="mt-1 w-5 h-5 rounded border-2 border-gray-300 text-primary">
                        <div>
                            <p class="text-sm font-semibold text-gray-700">Mostrar resultados al finalizar</p>
                            <p class="text-xs text-gray-500">El estudiante verá su nota inmediatamente</p>
                        </div>
                    </label>
                    <label class="flex items-start gap-3 p-3 bg-white rounded-sm cursor-pointer transition-all">
                        <input type="checkbox" name="permitir_revision" value="1"
                            {{ old('permitir_revision', $examen->permitir_revision) ? 'checked' : '' }}
                            class="mt-1 w-5 h-5 rounded border-2 border-gray-300 text-primary">
                        <div>
                            <p class="text-sm font-semibold text-gray-700">Permitir revisión después</p>
                            <p class="text-xs text-gray-500">El estudiante puede revisar sus respuestas</p>
                        </div>
                    </label>
                    <label class="flex items-start gap-3 p-3 bg-white rounded-sm cursor-pointer transition-all">
                        <input type="checkbox" name="navegacion_libre" value="1"
                            {{ old('navegacion_libre', $examen->navegacion_libre) ? 'checked' : '' }}
                            class="mt-1 w-5 h-5 rounded border-2 border-gray-300 text-primary">
                        <div>
                            <p class="text-sm font-semibold text-gray-700">Navegación libre entre preguntas</p>
                            <p class="text-xs text-gray-500">Puede ir y volver entre preguntas</p>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex justify-center gap-3">
                <button type="submit"
                    class="bg-primary text-accent px-6 py-3 rounded-sm font-bold hover:bg-primary/90 transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Actualizar Examen
                </button>
            </div>
        </form>
    </div>
@endsection
