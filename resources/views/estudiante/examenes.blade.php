@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-primary mb-1">Examenes</h1>
            <em class="text-gray-600 text-sm">{{ $curso->nombre }}</em>
        </div>
        <a href="{{ route('estudiante.dashboard') }}"
            class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Mis Cursos
        </a>
    </div>

    @if (session('error'))
        <div
            class="bg-red-50 border-2 border-red-300 text-red-800 px-4 py-3 rounded-sm mb-6 flex items-center gap-3 shadow-md">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid gap-2">
        @forelse ($examenes as $examen)
            @php
                $intentosRealizados = $examen->intentos->count();
                $intentosRestantes = $examen->intentos_permitidos - $intentosRealizados;
                $tieneIntentoActivo = $examen->intentos->where('estado', 'en_progreso')->count() > 0;
                $mejorIntento = $examen->intentos
                    ->where('estado', 'finalizado')
                    ->sortByDesc('puntaje_obtenido')
                    ->first();
            @endphp
            <div
                class="bg-white rounded-sm shadow-lg overflow-hidden border-2 {{ $tieneIntentoActivo ? 'border-yellow-400' : 'border-gray-400' }} hover:shadow-2xl transition-all">
                <!-- Header del Examen -->
                <div class="bg-primary p-4">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-bold text-sm text-accent mb-1 uppercase">{{ $examen->titulo }}</h3>
                            @if ($examen->descripcion)
                                <em class="text-xs text-white p-2">{{ $examen->descripcion }}</em>
                            @endif
                        </div>
                        <span
                            class="px-3 py-1 rounded-xs text-xs font-semibold bg-green-100 text-green-700 border border-green-300 flex items-center gap-1">
                            Disponible
                        </span>
                    </div>
                </div>

                <!-- Informacion del Examen -->
                <div class="p-5">
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span
                            class="px-3 py-1 rounded-sm text-xs bg-blue-100 text-blue-700 border border-blue-300">
                            {{ $examen->puntaje_total }} pts
                        </span>
                        @if ($examen->tiempo_limite)
                            <span
                                class="px-3 py-1 rounded-sm text-xs bg-yellow-100 text-yellow-700 border border-yellow-300">
                                {{ $examen->tiempo_limite }} min
                            </span>
                        @endif

                    </div>

                    <div
                        class="text-xs text-gray-500 mb-4 flex items-center gap-2 bg-gray-50 p-3 rounded-sm border border-gray-200">
                        <span class="text-xs">Disponible desde
                            <p class="p-2 text-black">{{ $examen->fecha_inicio->format('d/m/Y H:i') }}</p> hasta
                            <p class="p-2 text-black">{{ $examen->fecha_fin->format('d/m/Y H:i') }}</p></span>
                    </div>

                    @if ($intentosRealizados > 0)
                        <div class="border-t-2 border-gray-100 pt-4 mb-4 p-2">
                            <p class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                                Historial de Intentos
                            </p>
                            <div class="space-y-2 p-2">
                                @foreach ($examen->intentos->sortBy('numero_intento') as $intento)
                                    <div
                                        class="flex items-center justify-between text-sm bg-gray-50 px-4 py-3 rounded-sm border border-gray-200">
                                        <div class="flex items-center gap-4">
                                            <span class="px-3 py-1 rounded-sm text-xs">
                                                N°{{ $intento->numero_intento }}</span>
                                            @if ($intento->estado == 'finalizado')
                                                @php
                                                    $notaIntento =
                                                        $examen->puntaje_total > 0
                                                            ? round(
                                                                ($intento->puntaje_obtenido / $examen->puntaje_total) *
                                                                    20,
                                                                1,
                                                            )
                                                            : 0;
                                                @endphp
                                                <span
                                                    class="{{ $notaIntento >= 11 ? 'text-gray-700' : 'text-red-700' }} text-xs">
                                                    {{ $intento->puntaje_obtenido }}/{{ $examen->puntaje_total }} pts
                                                    ({{ $notaIntento }})
                                                </span>
                                                <span class="text-xs">{{ $intento->fin->format('d/m/Y H:i') }}</span>
                                            @else
                                                <span class="text-yellow-600 font-semibold flex items-center gap-1 text-xs">
                                                    En progreso
                                                </span>
                                            @endif
                                        </div>
                                        @if ($intento->estado == 'finalizado' && $examen->mostrar_resultados)
                                            <a href="{{ route('estudiante.resultado-examen', [$curso, $examen->id, $intento->id]) }}"
                                                class="flex items-center gap-1 bg-primary text-white px-3 py-2 rounded-sm text-xs font-semibold shadow-md">
                                                Ver resultado
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="flex gap-3">
                        @if ($tieneIntentoActivo)
                        
                            <form method="post" action="{{ route('estudiante.iniciar-examen', [$curso, $examen]) }}"
                                class="grow">
                                @csrf
                                <button
                                    class="w-full flex items-center justify-center gap-2 bg-yellow-500 text-white px-4 py-2.5 rounded-sm text-sm font-bold hover:bg-yellow-600 transition-all shadow-md">
                                    <svg class="w-4 h-4 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Continuar Examen en Progreso
                                </button>
                            </form>
                        @elseif($intentosRestantes > 0)
                            <form method="post" action="{{ route('estudiante.iniciar-examen', [$curso, $examen]) }}"
                                class="grow">
                                @csrf
                                <button
                                    class="w-full flex items-center justify-center gap-2 bg-primary text-accent px-4 py-2.5 rounded-sm text-sm font-bold hover:bg-primary/90 transition-all shadow-md">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $intentosRealizados > 0 ? 'Iniciar Nuevo Intento' : 'Iniciar Examen' }}
                                </button>
                            </form>
                        @else
                            <button disabled
                                class="w-full flex items-center justify-center gap-2 bg-gray-400 text-white px-4 py-2.5 rounded-sm text-sm font-bold cursor-not-allowed opacity-60">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Intentos Agotados
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-sm shadow-lg p-12 text-center border-2 border-dashed border-gray-300">
                <svg class="w-8 h-8 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <p class="text-gray-500 font-medium text-sm">No hay examenes disponibles</p>
                <p class="text-gray-400 text-xs mt-1">Este curso no tiene examenes publicados en este momento.</p>
            </div>
        @endforelse
    </div>
@endsection
