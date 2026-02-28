@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-primary mb-1">Exámenes</h1>
            <p class="text-gray-600">{{ $curso->nombre }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('docente.examenes.crear', $curso) }}"
                class="flex items-center gap-2 bg-primary text-accent px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary/90 transition-all shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo Examen
            </a>
            <a href="{{ route('docente.curso', $curso) }}"
                class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Curso
            </a>
        </div>
    </div>

    <div class="grid gap-4">
        @forelse ($examenes as $examen)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border-2 {{ $examen->preguntas_count == 0 ? 'border-red-300' : 'border-primary/20' }} hover:shadow-2xl transition-all">
                <!-- Header del examen -->
                <div class="bg-gradient-to-r {{ $examen->preguntas_count == 0 ? 'from-red-500 to-red-600' : 'from-primary to-primary-dark' }} p-4">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-accent mb-1">{{ $examen->titulo }}</h3>
                            @if($examen->descripcion)
                                <p class="text-accent/80 text-sm">{{ $examen->descripcion }}</p>
                            @endif
                            @if($examen->preguntas_count == 0)
                                <div class="mt-2 flex items-center gap-2 bg-red-600 px-3 py-1 rounded-full w-fit">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-white text-xs font-bold">Sin preguntas - Debe asignar preguntas antes de publicar</span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <span class="px-4 py-1.5 rounded-full text-sm font-bold
                                @if ($examen->estado == 'creado') bg-gray-100 text-gray-700 border-2 border-gray-300
                                @elseif($examen->estado == 'publicado') bg-green-100 text-green-700 border-2 border-green-300
                                @else bg-red-100 text-red-700 border-2 border-red-300 @endif">
                                {{ ucfirst($examen->estado) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Contenido del examen -->
                <div class="p-6">
                    <!-- Estadísticas -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">
                        <div class="bg-{{ $examen->preguntas_count == 0 ? 'red' : 'blue' }}-50 p-3 rounded-lg border-2 border-{{ $examen->preguntas_count == 0 ? 'red' : 'blue' }}-200">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-4 h-4 text-{{ $examen->preguntas_count == 0 ? 'red' : 'blue' }}-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-xs text-{{ $examen->preguntas_count == 0 ? 'red' : 'blue' }}-700 font-semibold">Preguntas</p>
                            </div>
                            <p class="text-2xl font-bold text-{{ $examen->preguntas_count == 0 ? 'red' : 'blue' }}-600">{{ $examen->preguntas_count }}</p>
                        </div>

                        <div class="bg-purple-50 p-3 rounded-lg border-2 border-purple-200">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <p class="text-xs text-purple-700 font-semibold">Puntaje</p>
                            </div>
                            <p class="text-2xl font-bold text-purple-600">{{ $examen->puntaje_total }}</p>
                        </div>

                        <div class="bg-yellow-50 p-3 rounded-lg border-2 border-yellow-200">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-xs text-yellow-700 font-semibold">Tiempo</p>
                            </div>
                            <p class="text-2xl font-bold text-yellow-600">{{ $examen->tiempo_limite ?? '∞' }}</p>
                            @if($examen->tiempo_limite)
                                <p class="text-xs text-yellow-600">minutos</p>
                            @endif
                        </div>

                        <div class="bg-green-50 p-3 rounded-lg border-2 border-green-200">
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-xs text-green-700 font-semibold">Intentos</p>
                            </div>
                            <p class="text-2xl font-bold text-green-600">{{ $examen->intentos_permitidos }}</p>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('docente.examenes.editar', [$curso, $examen]) }}"
                            class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition-all shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar
                        </a>
                        
                        <a href="{{ route('docente.examenes.asignar-preguntas', [$curso, $examen]) }}"
                            class="flex items-center gap-2 bg-purple-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-purple-700 transition-all shadow-md">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                            </svg>
                            Preguntas ({{ $examen->preguntas_count }})
                        </a>
                        
                        <a href="{{ route('docente.examenes.resultados', [$curso, $examen]) }}"
                            class="flex items-center gap-2 bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-teal-700 transition-all shadow-md">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                            </svg>
                            Resultados
                        </a>

                        @if ($examen->estado == 'creado')
                            <form method="post" action="{{ route('docente.examenes.publicar', [$curso, $examen]) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="flex items-center gap-2 bg-primary text-accent px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary/90 transition-all shadow-md">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Publicar
                                </button>
                            </form>
                        @elseif($examen->estado == 'publicado')
                            <form method="post" action="{{ route('docente.examenes.cerrar', [$curso, $examen]) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 transition-all shadow-md">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Cerrar
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-lg p-12 text-center border-2 border-dashed border-gray-300">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-gray-500 font-medium text-lg mb-2">No hay exámenes registrados</p>
                <p class="text-gray-400 text-sm mb-4">Crea tu primer examen para comenzar</p>
                <a href="{{ route('docente.examenes.crear', $curso) }}"
                    class="inline-flex items-center gap-2 bg-primary text-accent px-6 py-3 rounded-lg font-bold hover:bg-primary/90 transition-all shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Crear Examen
                </a>
            </div>
        @endforelse
    </div>

    @if($examenes->hasPages())
        <div class="mt-6">{{ $examenes->links() }}</div>
    @endif
@endsection
