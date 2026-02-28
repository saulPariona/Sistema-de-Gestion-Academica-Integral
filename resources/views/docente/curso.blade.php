@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-primary mb-1">{{ $curso->nombre }}</h1>
            <p class="text-gray-600">{{ $curso->periodo->nombre }}</p>
        </div>
        <a href="{{ route('docente.dashboard') }}" 
           class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Mis Cursos
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Información del Curso -->
        <div class="bg-white rounded-xl shadow-lg border-2 border-primary/20 overflow-hidden">
            <div class="bg-gradient-to-r from-primary to-primary-dark p-4">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-accent" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-accent">Información del Curso</h3>
                        <p class="text-accent/80 text-sm">Detalles y estadísticas</p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-start gap-3 p-3 bg-primary/5 rounded-lg">
                    <svg class="w-5 h-5 text-primary mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <p class="text-xs text-gray-600 mb-1">Descripción</p>
                        <p class="font-semibold text-gray-800">{{ $curso->descripcion ?? 'Sin descripción' }}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 bg-blue-50 rounded-lg border-2 border-blue-200">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                            </svg>
                            <p class="text-xs text-blue-700 font-semibold">Estudiantes</p>
                        </div>
                        <p class="text-2xl font-bold text-blue-600">{{ $curso->estudiantes->count() }}</p>
                    </div>
                    
                    <div class="p-3 bg-green-50 rounded-lg border-2 border-green-200">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-xs text-green-700 font-semibold">Exámenes</p>
                        </div>
                        <p class="text-2xl font-bold text-green-600">{{ $curso->examenes->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="bg-white rounded-xl shadow-lg border-2 border-primary/20 overflow-hidden">
            <div class="bg-gradient-to-r from-primary to-primary-dark p-4">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-accent" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-accent">Acciones Rápidas</h3>
                        <p class="text-accent/80 text-sm">Gestiona tu curso</p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-3">
                <a href="{{ route('docente.estudiantes', $curso) }}"
                    class="flex items-center gap-3 bg-blue-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-all shadow-md hover:shadow-lg group">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                    <span>Ver Estudiantes</span>
                    <svg class="w-4 h-4 ml-auto group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                
                <a href="{{ route('docente.examenes', $curso) }}"
                    class="flex items-center gap-3 bg-primary text-accent px-4 py-3 rounded-lg font-semibold hover:bg-primary/90 transition-all shadow-md hover:shadow-lg group">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                    <span>Gestionar Exámenes</span>
                    <svg class="w-4 h-4 ml-auto group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                
                <a href="{{ route('docente.banco-preguntas', $curso) }}"
                    class="flex items-center gap-3 bg-purple-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-purple-700 transition-all shadow-md hover:shadow-lg group">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                    <span>Banco de Preguntas</span>
                    <svg class="w-4 h-4 ml-auto group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                
                <a href="{{ route('docente.observaciones', $curso) }}"
                    class="flex items-center gap-3 bg-yellow-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-yellow-700 transition-all shadow-md hover:shadow-lg group">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                    <span>Observaciones</span>
                    <svg class="w-4 h-4 ml-auto group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                
                <a href="{{ route('docente.exportar-notas', $curso) }}"
                    class="flex items-center gap-3 bg-gray-700 text-white px-4 py-3 rounded-lg font-semibold hover:bg-gray-800 transition-all shadow-md hover:shadow-lg group">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    <span>Exportar Notas CSV</span>
                    <svg class="w-4 h-4 ml-auto group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
@endsection
