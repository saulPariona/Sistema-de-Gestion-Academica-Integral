@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-primary mb-1">{{ $curso->nombre }}</h1>
            <em class="text-gray-600 text-sm">{{ $curso->periodo->nombre }}</em>
        </div>
        <a href="{{ route('estudiante.dashboard') }}"
            class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Mis Cursos
        </a>
    </div>

    <div class="grid grid-cols-1 mb-6">
        <!-- Informacion del Curso -->
        <div class="bg-white rounded-sm shadow-lg border-2 border-gray-400 overflow-hidden">
            <div class="bg-primary p-4">
                <div class="items-center gap-3">
                    <div>
                        <h3 class="text-md font-bold text-accent">Informacion del Curso</h3>
                        <em class="text-xs text-white">Detalles y docente</em>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div class="items-start gap-3 p-3 rounded-sm">
                    <em class="text-xs text-gray-600 mb-2">Descripcion</em>
                    <p class="font-semibold text-sm text-gray-800 px-1">{{ $curso->descripcion ?? 'Sin descripcion' }}</p>
                </div>
                <div class="items-start gap-3 p-3 rounded-sm">
                    <em class="text-xs text-gray-600 mb-2">Docente</em>
                    <p class="font-semibold text-sm text-gray-800 px-1">{{ $curso->docentes->count() ? $curso->docentes->first()->nombreCompleto() : 'Sin asignar' }}</p>
                </div>
                <div class="items-start gap-3 p-3 rounded-sm">
                    <em class="text-xs text-gray-600 mb-2">Examenes</em>
                    <p class="font-semibold text-sm text-gray-800 px-1">{{ $examenes->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <h2 class="text-lg font-bold text-primary mb-4 flex items-center gap-2">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
            <path fill-rule="evenodd"
                d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                clip-rule="evenodd" />
        </svg>
        Examenes
    </h2>

    <div class="grid gap-2">
        @forelse ($examenes as $examen)
            <div class="bg-white rounded-sm shadow-lg p-6 border-2 border-gray-400 hover:shadow-2xl transition-all">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <div class="flex items-start gap-3 mb-3">
                            <h3 class="text-sm font-bold text-gray-800 flex-1 uppercase">{{ $examen->titulo }}</h3>
                            @if ($examen->fecha_inicio <= now() && $examen->fecha_fin >= now())
                                <span
                                    class="px-3 py-1 rounded-sm text-xs font-bold bg-green-100 text-green-700 border border-green-300">Disponible</span>
                            @else
                                <span
                                    class="px-3 py-1 rounded-sm text-xs font-bold bg-gray-100 text-gray-700 border border-gray-300">No
                                    disponible</span>
                            @endif
                        </div>

                        @if ($examen->descripcion)
                            <em class="text-gray-600 text-sm">{{ $examen->descripcion }}</em>
                        @endif

                        <div class="flex flex-wrap gap-2 mb-3 mt-3">
                            <span
                                class="px-3 py-1 rounded-sm text-xs font-bold bg-blue-100 text-blue-700 border border-blue-300">
                                {{ $examen->puntaje_total }} puntos
                            </span>
                            @if ($examen->tiempo_limite)
                                <span
                                    class="px-3 py-1 rounded-sm text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-300">
                                    {{ $examen->tiempo_limite }} min
                                </span>
                            @endif
                            <span
                                class="px-3 py-1 rounded-sm text-xs font-bold bg-gray-100 text-gray-700 border border-gray-300">
                                {{ $examen->fecha_inicio->format('d/m/Y H:i') }} -
                                {{ $examen->fecha_fin->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    </div>
                </div>
                @if ($examen->fecha_inicio <= now() && $examen->fecha_fin >= now())
                    <form method="post" action="{{ route('estudiante.iniciar-examen', [$curso, $examen]) }}"
                        class="mt-3">
                        @csrf
                        <button
                            class="flex items-center gap-1 bg-primary text-accent px-3 py-2 rounded-sm text-xs font-semibold hover:bg-primary/90 transition-all shadow-md">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                                    clip-rule="evenodd" />
                            </svg>
                            Rendir Examen
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-sm shadow-lg p-12 text-center border-2 border-dashed border-gray-300">
                <svg class="w-8 h-8 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-500 font-medium text-sm">No hay examenes disponibles en este momento</p>
            </div>
        @endforelse
    </div>
@endsection
