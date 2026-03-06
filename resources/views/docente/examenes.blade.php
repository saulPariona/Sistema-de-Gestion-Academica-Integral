@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-primary mb-1">Exámenes</h1>
            <em class="text-gray-600 text-sm">{{ $curso->nombre }}</em>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('docente.examenes.crear', $curso) }}"
                class="flex items-center gap-2 bg-primary text-accent px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary/90 transition-all shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nuevo Examen
            </a>
            <a href="{{ route('docente.curso', $curso) }}"
                class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Curso
            </a>
        </div>
    </div>

    <div class="grid gap-2">
        @forelse ($examenes as $examen)
            <div
                class="bg-white rounded-sm shadow-lg p-6 border-2 {{ $examen->preguntas_count == 0 ? 'border-red-300' : 'border-gray-400' }} hover:shadow-2xl transition-all">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <!-- Título y estado -->
                        <div class="flex items-start gap-3 mb-3">
                            <h3 class="text-sm font-bold text-gray-800 flex-1 uppercase">{{ $examen->titulo }}</h3>
                            <span
                                class="px-3 py-1 rounded-sm text-xs font-bold border
                                {{ $examen->estado == 'creado' ? 'bg-gray-100 text-gray-700 border-gray-300' : ($examen->estado == 'publicado' ? 'bg-green-100 text-green-700 border-green-300' : 'bg-red-100 text-red-700 border-red-300') }}">
                                {{ ucfirst($examen->estado) }}
                            </span>
                        </div>

                        <!-- Descripción -->
                        @if ($examen->descripcion)
                            <em class="text-gray-600 text-sm">{{ $examen->descripcion }}</em>
                        @endif

                        <!-- Badges de información -->
                        <div class="flex flex-wrap gap-2 mb-4 mt-3">
                            <span
                                class="px-3 py-1 rounded-sm text-xs font-bold {{ $examen->preguntas_count == 0 ? 'bg-red-100 text-red-700 border border-red-300' : 'bg-blue-100 text-blue-700 border border-blue-300' }}">
                                {{ $examen->preguntas_count }} preguntas
                            </span>
                            <span
                                class="px-3 py-1 rounded-sm text-xs font-bold bg-purple-100 text-purple-700 border border-purple-300">
                                {{ $examen->puntaje_total }} puntos
                            </span>
                            <span
                                class="px-3 py-1 rounded-sm text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-300">
                                {{ $examen->tiempo_limite ?? '∞' }} {{ $examen->tiempo_limite ? 'min' : '' }}
                            </span>
                            <span
                                class="px-3 py-1 rounded-sm text-xs font-bold bg-green-100 text-green-700 border border-green-300">
                                {{ $examen->intentos_permitidos }} intentos
                            </span>
                        </div>

                        <!-- Alerta si no tiene preguntas -->
                        @if ($examen->preguntas_count == 0)
                            <div class="items-center gap-2 bg-red-50 border-2 border-red-300 px-3 py-2 rounded-sm">
                                <span class="text-red-700 text-xs font-semibold">Sin preguntas - Debe asignar preguntas
                                    antes de publicar</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Acciones -->
                <div class="flex flex-wrap gap-2 mt-4">
                    <a href="{{ route('docente.examenes.editar', [$curso, $examen]) }}"
                        class="flex items-center gap-1 bg-blue-600 text-white px-3 py-2 rounded-sm text-xs font-semibold hover:bg-blue-700 transition-all shadow-md">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar
                    </a>

                    <a href="{{ route('docente.examenes.asignar-preguntas', [$curso, $examen]) }}"
                        class="flex items-center gap-1 bg-purple-600 text-white px-3 py-2 rounded-sm text-xs font-semibold hover:bg-purple-700 transition-all shadow-md">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                clip-rule="evenodd" />
                        </svg>
                        Preguntas ({{ $examen->preguntas_count }})
                    </a>

                    <a href="{{ route('docente.examenes.resultados', [$curso, $examen]) }}"
                        class="flex items-center gap-1 bg-teal-600 text-white px-3 py-2 rounded-sm text-xs font-semibold hover:bg-teal-700 transition-all shadow-md">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                        </svg>
                        Resultados
                    </a>

                    @if ($examen->estado == 'creado')
                        <form method="post" action="{{ route('docente.examenes.publicar', [$curso, $examen]) }}"
                            class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="flex items-center gap-1 bg-primary text-accent px-3 py-2 rounded-sm text-xs font-semibold hover:bg-primary/90 transition-all shadow-md">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                Publicar
                            </button>
                        </form>
                    @elseif($examen->estado == 'publicado')
                        <form method="post" action="{{ route('docente.examenes.cerrar', [$curso, $examen]) }}"
                            class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="flex items-center gap-1 bg-red-600 text-white px-3 py-2 rounded-sm text-xs font-semibold hover:bg-red-700 transition-all shadow-md">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                                Cerrar
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-sm shadow-lg p-12 text-center border-2 border-dashed border-gray-300">
                <svg class="w-8 h-8 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-500 font-medium text-sm">No hay exámenes registrados</p>
            </div>
        @endforelse
    </div>

    @if ($examenes->hasPages())
        <div class="mt-6">{{ $examenes->links() }}</div>
    @endif
@endsection
