@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-primary mb-1">{{ $curso->nombre }}</h1>
            <em class="text-gray-600 text-sm">{{ $curso->periodo->nombre }}</em>
        </div>
        <a href="{{ route('docente.dashboard') }}"
            class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Mis Cursos
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <!-- Información del Curso -->
        <div class="bg-white rounded-sm shadow-lg border-2 border-gray-400 overflow-hidden">
            <div class="bg-primary p-4">
                <div class="items-center gap-3">
                    <div>
                        <h3 class="text-md font-bold text-accent">Información del Curso</h3>
                        <em class="text-xs text-white">Detalles y estadísticas</em>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div class="items-start gap-3 p-3 rounded-sm">
                    <em class="text-xs text-gray-600 mb-2">Descripción</em>
                    <p class="font-semibold text-sm text-gray-800">{{ $curso->descripcion ?? 'Sin descripción' }}</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 bg-green-50 rounded-sm border-2 border-green-200">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                            </svg>
                            <p class="text-xs text-green-700 font-semibold">Estudiantes</p>
                        </div>
                        <p class="text-2xl font-bold text-green-600">{{ $curso->estudiantes->count() }}</p>
                    </div>

                    <div class="p-3 bg-green-50 rounded-sm border-2 border-green-200">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                <path fill-rule="evenodd"
                                    d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                    clip-rule="evenodd" />
                            </svg>
                            <p class="text-xs text-green-700 font-semibold">Exámenes</p>
                        </div>
                        <p class="text-2xl font-bold text-green-600">{{ $curso->examenes->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="bg-white rounded-sm shadow-lg border-2 border-gray-400 overflow-hidden">
            <div class="bg-primary p-4">
                <div class="items-center gap-3">
                    <div>
                        <h3 class="text-lg font-bold text-accent">Acciones Rápidas</h3>
                        <em class="text-xs text-white">Gestiona tu curso</em>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-3">
                <a href="{{ route('docente.estudiantes', $curso) }}"
                    class="flex items-center gap-3 bg-gray-200 border-2 border-gray-400 text-gray-800 text-sm p-3 rounded-xs font-semibold transition-all hover:border-gray-600 group">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                    <span>Ver Estudiantes</span>
                    <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                <a href="{{ route('docente.examenes', $curso) }}"
                    class="flex items-center gap-3 bg-gray-200 border-2 border-gray-400 text-gray-800 text-sm p-3 rounded-xs font-semibold transition-all hover:border-gray-600 group">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                        <path fill-rule="evenodd"
                            d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Gestionar Exámenes</span>
                    <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                <a href="{{ route('docente.banco-preguntas', $curso) }}"
                    class="flex items-center gap-3 bg-gray-200 border-2 border-gray-400 text-gray-800 text-sm p-3 rounded-xs font-semibold transition-all hover:border-gray-600 group">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Banco de Preguntas</span>
                    <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                <a href="{{ route('docente.observaciones', $curso) }}"
                    class="flex items-center gap-3 bg-gray-200 border-2 border-gray-400 text-gray-800 text-sm p-3 rounded-xs font-semibold transition-all hover:border-gray-600 group">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Observaciones</span>
                    <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>

                <a href="{{ route('docente.exportar-notas', $curso) }}"
                    class="flex items-center gap-3 bg-gray-200 border-2 border-gray-400 text-gray-800 text-sm p-3 rounded-xs font-semibold transition-all hover:border-gray-600 group">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Exportar Notas CSV</span>
                    <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
@endsection
