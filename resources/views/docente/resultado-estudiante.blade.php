@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-primary mb-1">Detalle del Intento</h1>
            <em class="text-gray-600 text-sm">{{ $intento->estudiante->nombreCompleto() }}</em>
        </div>
        <a href="{{ route('docente.examenes.resultados', [$curso, $examen]) }}"
            class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Resultados
        </a>
    </div>

    <div class="bg-white rounded-sm shadow-lg mb-6 p-3 border-2 border-green-600">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="flex items-center gap-3">
                <div class="rounded-sm">
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Examen</p>
                    <p class="font-bold text-gray-800 text-xs">{{ $examen->titulo }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="rounded-sm">
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Intento</p>
                    <p class="font-bold text-gray-800 text-xs">N° {{ $intento->numero_intento }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="rounded-sm">
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Nota (base 20)</p>
                    @php
                        $notaBase20 = $examen->puntaje_total > 0
                            ? round(($intento->puntaje_obtenido / $examen->puntaje_total) * 20, 1)
                            : 0;
                    @endphp
                    <p class="font-bold text-xs {{ $notaBase20 >= 11 ? 'text-green-600' : 'text-red-600' }}">{{ $notaBase20 }} / 20</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="rounded-sm">
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Estado</p>
                    <p class="font-bold text-gray-800 text-xs">{{ ucfirst($intento->estado) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-3">
        @foreach ($intento->respuestas as $index => $respuesta)
            <div
                class="bg-white rounded-sm shadow-lg p-6 border-2 {{ $respuesta->esCorrecta() ? 'border-green-300' : 'border-red-300' }} hover:shadow-2xl transition-all">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-start gap-3 flex-1">
                        <p class="font-semibold text-gray-800 text-sm"><span
                                class="text-primary">{{ $index + 1 }}.</span> {{ $respuesta->pregunta->texto }}
                        </p>
                    </div>
                    <span
                        class="px-3 py-1 rounded-sm text-xs font-bold border {{ $respuesta->esCorrecta() ? 'bg-green-100 text-green-700 border-green-300' : 'bg-red-100 text-red-700 border-red-300' }}">
                        {{ $respuesta->esCorrecta() ? 'Correcta' : 'Incorrecta' }}
                    </span>
                </div>
                <div class="space-y-2 ml-10">
                    @foreach ($respuesta->pregunta->alternativas as $alt)
                        <div
                            class="flex items-center gap-3 text-sm p-2 rounded-sm transition-all
                        @if ($alt->es_correcta) bg-green-50 border-2 border-green-300 text-green-800 font-semibold text-xs
                        @elseif($alt->id == $respuesta->alternativa_id && !$alt->es_correcta) bg-red-50 border-2 border-red-300 text-red-800 font-semibold text-xs
                        @elseif(!$alt->es_correcta) bg-gray-50 border border-gray-200 text-gray-800 font-semibold text-xs
                        @else @endif">
                            @if ($alt->id == $respuesta->alternativa_id)
                                @if ($alt->es_correcta)
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-red-600 " fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                @endif
                            @elseif ($alt->es_correcta)
                                <svg class="w-5 h-5 text-green-600 " fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            @else
                                <div class="w-5 h-5 rounded-sm border-2 border-gray-400 "></div>
                            @endif
                            <span>{{ $alt->texto }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endsection
