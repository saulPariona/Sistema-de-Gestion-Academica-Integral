@extends('layouts.app')
@section('contenido')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-primary mb-1">Mis Cursos</h1>
        <em class="text-gray-600 text-sm">Bienvenido, {{ auth()->user()->nombres }}. Aqui puedes ver todos tus cursos
            activos.</em>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($cursos as $curso)
            <div
                class="bg-white rounded-sm shadow-lg overflow-hidden border-2 {{ $curso->examenesNuevos > 0 || $curso->examenesEnProgreso > 0 ? 'border-yellow-400' : 'border-gray-400' }} hover:shadow-2xl transition-all">
                <!-- Header del Curso -->
                <div class="relative h-28 bg-primary overflow-hidden">
                    <div class="absolute inset-0 bg-pattern opacity-20"></div>
                    <div class="relative h-full p-4 flex flex-col justify-end">
                        <h2 class="font-bold text-lg text-accent leading-tight">{{ $curso->nombre }}</h2>
                        <em class="text-xs text-accent/80 mt-1 flex items-center gap-2 text-white">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $curso->periodo->nombre }}
                        </em>
                    </div>
                </div>

                <!-- Contenido del Curso -->
                <div class="p-4">
                    <em class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $curso->descripcion }}</em>

                    @if ($curso->docentes->count())
                        <div class="flex items-center gap-2 mb-4 text-xs text-gray-500">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                            </svg>
                            <span>{{ $curso->docentes->first()->nombreCompleto() }}</span>
                        </div>
                    @endif

                    @if ($curso->examenesNuevos > 0 || $curso->examenesEnProgreso > 0)
                        <div class="mb-4 bg-yellow-50 border-2 border-yellow-300 rounded-sm p-3">
                            @if ($curso->examenesEnProgreso > 0)
                                <p class="text-xs text-yellow-800 font-semibold flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $curso->examenesEnProgreso }} examen(es) en progreso
                                </p>
                            @endif
                            @if ($curso->examenesNuevos > 0)
                                <p
                                    class="text-xs text-yellow-800 flex items-center gap-2 {{ $curso->examenesEnProgreso > 0 ? 'mt-1' : '' }}">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                        <path fill-rule="evenodd"
                                            d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $curso->examenesNuevos }} examen(es) nuevo(s)
                                </p>
                            @endif
                        </div>
                    @endif

                    <div class="space-y-2">

                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('estudiante.curso', $curso) }}"
                                class="flex items-center justify-center gap-2 w-full bg-primary text-white px-4 py-2.5 rounded-sm text-xs">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                </svg>
                                Ver Curso
                            </a>
                            <a href="{{ route('estudiante.examenes', $curso) }}"
                                class="relative flex items-center justify-center gap-2 bg-primary-dark text-white px-3 py-2 rounded-sm text-xs">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                    <path fill-rule="evenodd"
                                        d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                        clip-rule="evenodd" />
                                </svg>
                                Examenes
                                @if ($curso->examenesNuevos > 0 || $curso->examenesEnProgreso > 0)
                                    <span
                                        class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-sm w-5 h-5 flex items-center justify-center">
                                        {{ $curso->examenesNuevos + $curso->examenesEnProgreso }}
                                    </span>
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3">
                <div class="bg-white rounded-sm shadow-lg p-12 text-center border-2 border-dashed border-gray-300">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <p class="text-gray-500 font-medium text-xs">No tienes cursos matriculados</p>
                    <p class="text-gray-400 text-xs mt-1">Contacta con la administracion para realizar tu matricula.</p>
                </div>
            </div>
        @endforelse
    </div>
@endsection
