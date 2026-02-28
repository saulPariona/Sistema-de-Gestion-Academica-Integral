@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Detalle - {{ $intento->estudiante->nombreCompleto() }}</h1>
        <a href="{{ route('docente.examenes.resultados', [$curso, $examen]) }}"
            class="text-blue-600 hover:underline text-sm">← Resultados</a>
    </div>

    <div class="bg-white rounded border border-gray-200 mb-4 p-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div><span class="font-semibold">Examen:</span> {{ $examen->titulo }}</div>
            <div><span class="font-semibold">Intento:</span> #{{ $intento->numero_intento }}</div>
            <div><span class="font-semibold">Puntaje:</span> {{ $intento->puntaje_obtenido ?? '-' }} /
                {{ $examen->puntaje_total }}</div>
            <div><span class="font-semibold">Estado:</span> {{ ucfirst($intento->estado) }}</div>
        </div>
    </div>

    <div class="space-y-4">
        @foreach ($intento->respuestas as $index => $respuesta)
            <div
                class="bg-white rounded border {{ $respuesta->esCorrecta() ? 'border-green-300' : 'border-red-300' }} p-4">
                <div class="flex justify-between items-start mb-2">
                    <p class="font-semibold text-gray-800">{{ $index + 1 }}. {{ $respuesta->pregunta->texto }}</p>
                    <span
                        class="px-2 py-1 rounded text-xs {{ $respuesta->esCorrecta() ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $respuesta->esCorrecta() ? 'Correcta' : 'Incorrecta' }}
                    </span>
                </div>
                <div class="space-y-1 ml-4">
                    @foreach ($respuesta->pregunta->alternativas as $alt)
                        <div
                            class="text-sm flex items-center gap-2
                            @if ($alt->id == $respuesta->alternativa_id && $alt->es_correcta) text-green-700 font-semibold
                            @elseif($alt->id == $respuesta->alternativa_id && !$alt->es_correcta) text-red-700 font-semibold
                            @elseif($alt->es_correcta) text-green-600
                            @else text-gray-600 @endif">
                            @if ($alt->id == $respuesta->alternativa_id)
                                ●
                            @else
                                ○
                            @endif
                            {{ $alt->texto }}
                            @if ($alt->es_correcta)
                                <span class="text-xs text-green-500">(correcta)</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endsection
