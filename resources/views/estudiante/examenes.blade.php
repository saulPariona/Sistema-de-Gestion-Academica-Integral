@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-primary mb-1">Exámenes</h1>
            <p class="text-gray-600">{{ $curso->nombre }}</p>
        </div>
        <a href="{{ route('estudiante.curso', $curso) }}" 
           class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver al Curso
        </a>
    </div>

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-r mb-6 flex items-center gap-3 shadow-md">
            <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="space-y-6">
        @forelse ($examenes as $examen)
            @php
                $intentosRealizados = $examen->intentos->count();
                $intentosRestantes = $examen->intentos_permitidos - $intentosRealizados;
                $tieneIntentoActivo = $examen->intentos->where('estado', 'en_progreso')->count() > 0;
                $mejorIntento = $examen->intentos->where('estado', 'finalizado')->sortByDesc('puntaje_obtenido')->first();
            @endphp
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border-2 {{ $tieneIntentoActivo ? 'border-yellow-400' : ($intentosRestantes > 0 ? 'border-primary/30' : 'border-gray-300') }} transition-all hover:shadow-2xl">
                <!-- Header del Examen -->
                <div class="bg-gradient-to-r from-primary to-primary-dark p-5">
                    <div class="flex justify-between items-start">
                        <div class="grow">
                            <h3 class="font-bold text-xl text-accent mb-1">{{ $examen->titulo }}</h3>
                            @if ($examen->descripcion)
                                <p class="text-sm text-accent/80">{{ $examen->descripcion }}</p>
                            @endif
                        </div>
                        <span class="px-4 py-1.5 bg-green-500 text-white text-xs rounded-full font-bold shadow-md flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Disponible
                        </span>
                    </div>
                </div>

                <!-- Información del Examen -->
                <div class="p-5">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">
                        <div class="bg-primary/5 rounded-lg p-3 border border-primary/20">
                            <p class="text-xs text-gray-600 mb-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Puntaje
                            </p>
                            <p class="font-bold text-lg text-primary">{{ $examen->puntaje_total }} pts</p>
                        </div>
                        @if ($examen->tiempo_limite)
                            <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-200">
                                <p class="text-xs text-gray-600 mb-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    Duración
                                </p>
                                <p class="font-bold text-lg text-yellow-700">{{ $examen->tiempo_limite }} min</p>
                            </div>
                        @endif
                        <div class="bg-{{ $intentosRestantes > 0 ? 'green' : 'red' }}-50 rounded-lg p-3 border border-{{ $intentosRestantes > 0 ? 'green' : 'red' }}-200">
                            <p class="text-xs text-gray-600 mb-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                </svg>
                                Intentos
                            </p>
                            <p class="font-bold text-lg text-{{ $intentosRestantes > 0 ? 'green' : 'red' }}-700">
                                {{ $intentosRestantes }} / {{ $examen->intentos_permitidos }}
                            </p>
                        </div>
                        @if($mejorIntento)
                            <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                                <p class="text-xs text-gray-600 mb-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    Mejor Nota
                                </p>
                                @php
                                    $nota = $examen->puntaje_total > 0 ? round(($mejorIntento->puntaje_obtenido / $examen->puntaje_total) * 20, 1) : 0;
                                @endphp
                                <p class="font-bold text-lg {{ $nota >= 11 ? 'text-green-600' : 'text-red-600' }}">{{ $nota }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="text-xs text-gray-500 mb-4 flex items-center gap-2 bg-gray-50 p-3 rounded-lg">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Disponible desde <strong>{{ $examen->fecha_inicio->format('d/m/Y H:i') }}</strong> hasta <strong>{{ $examen->fecha_fin->format('d/m/Y H:i') }}</strong></span>
                    </div>

                    @if($intentosRealizados > 0)
                        <div class="border-t-2 border-gray-100 pt-4 mb-4">
                            <p class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Historial de Intentos
                            </p>
                            <div class="space-y-2">
                                @foreach($examen->intentos->sortBy('numero_intento') as $intento)
                                    <div class="flex items-center justify-between text-sm bg-gradient-to-r from-gray-50 to-white px-4 py-3 rounded-lg border border-gray-200 hover:shadow-md transition-all">
                                        <div class="flex items-center gap-4">
                                            <span class="font-bold text-primary bg-primary/10 px-3 py-1 rounded-full">Intento #{{ $intento->numero_intento }}</span>
                                            @if($intento->estado == 'finalizado')
                                                @php
                                                    $notaIntento = $examen->puntaje_total > 0 ? round(($intento->puntaje_obtenido / $examen->puntaje_total) * 20, 1) : 0;
                                                @endphp
                                                <span class="{{ $notaIntento >= 11 ? 'text-green-600' : 'text-red-600' }} font-bold text-base">
                                                    {{ $intento->puntaje_obtenido }}/{{ $examen->puntaje_total }} pts ({{ $notaIntento }})
                                                </span>
                                                <span class="text-gray-500 text-xs">{{ $intento->fin->format('d/m/Y H:i') }}</span>
                                            @else
                                                <span class="text-yellow-600 font-semibold flex items-center gap-1">
                                                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                    </svg>
                                                    En progreso
                                                </span>
                                            @endif
                                        </div>
                                        @if($intento->estado == 'finalizado' && $examen->mostrar_resultados)
                                            <a href="{{ route('estudiante.resultado-examen', [$curso, $examen->id, $intento->id]) }}" 
                                               class="flex items-center gap-1 text-primary hover:text-primary/80 font-semibold text-xs bg-accent px-3 py-1.5 rounded-lg hover:shadow-md transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Ver resultado
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="flex gap-3">
                        @if($tieneIntentoActivo)
                            <form method="post" action="{{ route('estudiante.iniciar-examen', [$curso, $examen]) }}" class="grow">
                                @csrf
                                <button class="w-full bg-yellow-500 text-white px-6 py-3 rounded-lg text-sm font-bold hover:bg-yellow-600 transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                    </svg>
                                    Continuar Examen en Progreso
                                </button>
                            </form>
                        @elseif($intentosRestantes > 0)
                            <form method="post" action="{{ route('estudiante.iniciar-examen', [$curso, $examen]) }}" class="grow">
                                @csrf
                                <button class="w-full bg-primary text-accent px-6 py-3 rounded-lg text-sm font-bold hover:bg-primary/90 transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $intentosRealizados > 0 ? 'Iniciar Nuevo Intento' : 'Iniciar Examen' }}
                                </button>
                            </form>
                        @else
                            <button disabled class="w-full bg-gray-400 text-white px-6 py-3 rounded-lg text-sm font-bold cursor-not-allowed flex items-center justify-center gap-2 opacity-60">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                </svg>
                                Intentos Agotados
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-lg p-12 text-center border-2 border-dashed border-gray-300">
                <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-xl font-bold text-gray-600 mb-2">No hay exámenes disponibles</h3>
                <p class="text-gray-500">Este curso no tiene exámenes publicados en este momento.</p>
            </div>
        @endforelse
    </div>
@endsection
