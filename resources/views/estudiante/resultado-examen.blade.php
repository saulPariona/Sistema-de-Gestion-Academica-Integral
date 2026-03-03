@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-primary mb-1">Resultado</h1>
            <em class="text-gray-600 text-sm p-2">{{ $examen->titulo }} — Intento N° {{ $intento->numero_intento }}</em>
        </div>
        <a href="{{ route('estudiante.examenes', $curso) }}"
            class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver
        </a>
    </div>

    @php
        $nota = $examen->puntaje_total > 0 ? round(($intento->puntaje_obtenido / $examen->puntaje_total) * 20, 1) : 0;
    @endphp

    <div class="bg-white rounded-sm shadow-lg mb-6 p-3 border-2 border-green-600">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="flex items-center gap-3">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Puntaje Obtenido</p>
                    <p class="font-bold text-xs text-gray-800">{{ $intento->puntaje_obtenido ?? 0 }} / {{ $examen->puntaje_total }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Nota (base 20)</p>
                    <span class="px-2 py-1 rounded-xs text-xs font-semibold {{ $nota >= 11 ? 'text-green-700' : 'text-red-700' }}">
                        {{ $nota }}
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Estado</p>
                    <span class="px-2 py-1 rounded-xs text-xs font-semibold {{ $nota >= 11 ? 'text-green-700' : 'text-red-700' }}">
                        {{ $nota >= 11 ? 'Aprobado' : 'Desaprobado' }}
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Intento</p>
                    <p class="font-bold text-xs text-gray-800">N° {{ $intento->numero_intento }}</p>
                </div>
            </div>
        </div>
    </div>

    @if ($examen->mostrar_resultados)
        <div class="bg-white rounded-sm shadow-lg overflow-hidden border-2 border-gray-300">
            <div class="bg-primary p-4">
                <h2 class="text-xs font-bold text-white">Detalle de Respuestas</h2>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach ($examen->preguntas as $index => $pregunta)
                    @php
                        $respuesta = $intento->respuestas->where('pregunta_id', $pregunta->id)->first();
                        $esCorrecta = $respuesta?->esCorrecta() ?? false;
                        $fueRespondida = $respuesta !== null;
                    @endphp
                    <div class="p-4 hover:bg-primary/5 transition-all">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex-1">
                                <p class="font-medium text-xs text-gray-800">{{ $index + 1 }}. {{ $pregunta->texto }}</p>
                                @if ($pregunta->imagen)
                                    <img src="{{ asset('storage/' . $pregunta->imagen) }}" alt="Imagen de pregunta"
                                        class="mt-2 mb-3 max-h-48 rounded-sm border border-gray-200">
                                @endif
                            </div>
                            <span class="px-2 py-1 rounded-xs text-xs font-semibold
                                {{ $fueRespondida ? ($esCorrecta ? 'text-green-700' : 'text-red-700') : 'text-gray-500' }}">
                                {{ $fueRespondida ? ($esCorrecta ? 'Correcta' : 'Incorrecta') : 'No respondida' }}
                            </span>
                        </div>
                        @if ($examen->permitir_revision)
                            <div class="space-y-1 ml-4">
                                @foreach ($pregunta->alternativas as $alt)
                                    @php
                                        $fueSeleccionada = $respuesta && $alt->id == $respuesta->alternativa_id;
                                    @endphp
                                    <div class="p-2 rounded-xs text-xs flex items-center gap-2
                                        @if ($fueSeleccionada && $alt->es_correcta) bg-green-50 border border-green-300 text-green-700 font-semibold
                                        @elseif($fueSeleccionada) bg-red-50 border border-red-300 text-red-700 font-semibold
                                        @elseif($alt->es_correcta) bg-green-50 border border-green-200 text-green-600 font-semibold
                                        @else text-gray-600 border border-transparent @endif">
                                        <span class="text-xs">
                                            @if($fueSeleccionada)
                                                {{ $alt->es_correcta ? '✓' : '✗' }}
                                            @elseif($alt->es_correcta)
                                                ✓
                                            @else
                                                ○
                                            @endif
                                        </span>
                                        <div class="flex-1">
                                            {{ $alt->texto }}
                                            @if ($alt->es_correcta && !$fueSeleccionada)
                                                <span class="text-xs text-green-500">(Respuesta correcta)</span>
                                            @endif
                                        </div>
                                        @if ($alt->imagen)
                                            <img src="{{ asset('storage/' . $alt->imagen) }}" alt="Imagen de alternativa"
                                                class="max-h-16 rounded-sm border">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-white rounded-sm shadow-lg overflow-hidden border-2 border-gray-300">
            <div class="p-12">
                <div class="text-center">
                    <svg class="w-8 h-8 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-gray-500 font-medium text-xs">El docente ha configurado este examen para no mostrar las respuestas detalladas.</p>
                </div>
            </div>
        </div>
    @endif
@endsection
