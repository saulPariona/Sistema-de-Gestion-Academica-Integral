@extends('layouts.app')
@section('contenido')
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-primary mb-2">Mis Cursos</h1>
        <p class="text-gray-600">Bienvenido, {{ auth()->user()->nombres }}. Aquí puedes ver todos tus cursos activos.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($cursos as $curso)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border-2 {{ $curso->examenesNuevos > 0 || $curso->examenesEnProgreso > 0 ? 'border-yellow-400 shadow-yellow-200' : 'border-primary/20' }} hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                <!-- Header del Curso con Imagen de Fondo -->
                <div class="relative h-32 bg-gradient-to-br from-primary to-primary-dark overflow-hidden">
                    <div class="absolute inset-0 bg-pattern opacity-20"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    <div class="relative h-full p-4 flex flex-col justify-end">
                        <h2 class="font-bold text-xl text-accent leading-tight">{{ $curso->nombre }}</h2>
                        <p class="text-xs text-accent/80 mt-1">{{ $curso->periodo->nombre }}</p>
                    </div>
                    
                    <!-- Badge de Notificación -->
                    @if($curso->examenesNuevos > 0 || $curso->examenesEnProgreso > 0)
                        <div class="absolute top-3 right-3">
                            <span class="inline-flex items-center gap-1 bg-yellow-400 text-primary text-xs font-bold px-3 py-1.5 rounded-full shadow-lg animate-pulse">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                {{ $curso->examenesNuevos + $curso->examenesEnProgreso }}
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Contenido del Curso -->
                <div class="p-4">
                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $curso->descripcion }}</p>
                    
                    @if ($curso->docentes->count())
                        <div class="flex items-center gap-2 mb-4 text-xs text-gray-500">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                            </svg>
                            <span>{{ $curso->docentes->first()->nombreCompleto() }}</span>
                        </div>
                    @endif
                    
                    <!-- Alerta de Exámenes -->
                    @if($curso->examenesNuevos > 0 || $curso->examenesEnProgreso > 0)
                        <div class="mb-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r p-3">
                            @if($curso->examenesEnProgreso > 0)
                                <p class="text-xs text-yellow-800 font-semibold flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $curso->examenesEnProgreso }} examen(es) en progreso
                                </p>
                            @endif
                            @if($curso->examenesNuevos > 0)
                                <p class="text-xs text-yellow-800 flex items-center gap-2 {{ $curso->examenesEnProgreso > 0 ? 'mt-1' : '' }}">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $curso->examenesNuevos }} examen(es) nuevo(s)
                                </p>
                            @endif
                        </div>
                    @endif

                    <!-- Botones de Acción -->
                    <div class="flex gap-2">
                        <a href="{{ route('estudiante.curso', $curso) }}"
                            class="flex-1 bg-primary text-accent px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-primary/90 transition-all text-center shadow-md hover:shadow-lg">
                            Ver Curso
                        </a>
                        <a href="{{ route('estudiante.examenes', $curso) }}"
                            class="relative flex-1 bg-primary-dark text-accent px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-primary-dark/90 transition-all text-center shadow-md hover:shadow-lg">
                            Exámenes
                            @if($curso->examenesNuevos > 0 || $curso->examenesEnProgreso > 0)
                                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center shadow-lg">
                                    {{ $curso->examenesNuevos + $curso->examenesEnProgreso }}
                                </span>
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3">
                <div class="bg-white rounded-xl shadow-lg p-12 text-center border-2 border-dashed border-gray-300">
                    <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-600 mb-2">No tienes cursos matriculados</h3>
                    <p class="text-gray-500">Contacta con la administración para realizar tu matrícula.</p>
                </div>
            </div>
        @endforelse
    </div>
@endsection
