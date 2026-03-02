@extends('layouts.app')
@section('contenido')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-primary mb-1">Panel del Docente</h1>
        <em class="text-gray-600 text-sm">Bienvenido, {{ auth()->user()->nombres }}. Gestiona tus cursos y evaluaciones.</em>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($cursos as $curso)
            <div class="bg-white rounded-sm shadow-lg overflow-hidden border-2 border-gray-400 hover:shadow-2xl transition-all">
                <!-- Header del Curso -->
                <div class="relative h-28 bg-primary overflow-hidden">
                    <div class="absolute inset-0 bg-pattern opacity-20"></div>
                    <div class="relative h-full p-4 flex flex-col justify-end">
                        <h2 class="font-bold text-lg text-accent leading-tight">{{ $curso->nombre }}</h2>
                        <em class="text-xs text-accent/80 mt-1 flex items-center gap-2 text-white">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            {{ $curso->periodo->nombre }}
                        </em>
                    </div>
                    
                    <!-- Icono Decorativo -->
                    <div class="absolute top-3 right-3 bg-accent/20 rounded-sm p-2">
                        <svg class="w-5 h-5 text-accent" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                        </svg>
                    </div>
                </div>

                <!-- Contenido del Curso -->
                <div class="p-4">
                    <em class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $curso->descripcion }}</em>
                    
                    <!-- Botones de Acción -->
                    <div class="space-y-2">
                        <a href="{{ route('docente.curso', $curso) }}"
                            class="flex items-center justify-center gap-2 w-full bg-primary text-accent px-4 py-2.5 rounded-sm text-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                            </svg>
                            Ver Curso
                        </a>
                        
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('docente.examenes', $curso) }}"
                                class="flex items-center justify-center gap-2 bg-primary-dark text-accent px-3 py-2 rounded-sm text-xs">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                </svg>
                                Exámenes
                            </a>
                            <a href="{{ route('docente.banco-preguntas', $curso) }}"
                                class="flex items-center justify-center gap-2 bg-yellow-600 text-white px-3 py-2 rounded-sm text-xs">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                </svg>
                                Preguntas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3">
                <div class="bg-white rounded-sm shadow-lg p-12 text-center border-2 border-dashed border-gray-300">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <p class="text-gray-500 font-medium text-lg">No tienes cursos asignados</p>
                    <p class="text-gray-400 text-sm mt-1">Contacta con la administración para la asignación de cursos.</p>
                </div>
            </div>
        @endforelse
    </div>
@endsection
