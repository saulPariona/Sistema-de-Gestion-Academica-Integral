@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Resultado - {{ $examen->titulo }}</h1>
        <a href="{{ route('estudiante.curso', $curso) }}" class="text-blue-600 hover:underline text-sm">← Volver al
            Curso</a>
    </div>

    <div class="bg-white rounded border border-gray-200 p-4 mb-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Puntaje Obtenido</p>
                <p class="text-2xl font-bold text-gray-800">{{ $intento->puntaje_obtenido ?? 0 }} /
                    {{ $examen->puntaje_total }}</p>
            </div>
            <div>
                <p class="text-gray-500">Nota (base 20)</p>
                @php
                    $nota =
                        $examen->puntaje_total > 0
                            ? round(($intento->puntaje_obtenido / $examen->puntaje_total) * 20, 1)
                            : 0;
                @endphp
                <p class="text-2xl font-bold {{ $nota >= 11 ? 'text-green-600' : 'text-red-600' }}">{{ $nota }}</p>
            </div>
            <div>
                <p class="text-gray-500">Estado</p>
                <p class="text-lg font-semibold {{ $nota >= 11 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $nota >= 11 ? 'Aprobado' : 'Desaprobado' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Intento</p>
                <p class="text-lg font-semibold text-gray-800">#{{ $intento->numero_intento }}</p>
            </div>
        </div>
    </div>

    @if ($examen->mostrar_resultados)
        <h2 class="text-lg font-semibold text-gray-800 mb-3">Detalle de Respuestas</h2>
        <div class="space-y-4">
            @foreach ($examen->preguntas as $index => $pregunta)
                @php
                    $respuesta = $intento->respuestas->where('pregunta_id', $pregunta->id)->first();
                    $esCorrecta = $respuesta?->esCorrecta() ?? false;
                    $fueRespondida = $respuesta !== null;
                @endphp
                <div
                    class="bg-white rounded border 
                    {{ $fueRespondida ? ($esCorrecta ? 'border-green-300' : 'border-red-300') : 'border-gray-300' }} p-4">
                    <div class="flex justify-between items-start mb-2">
                        <div class="grow">
                            <p class="font-semibold text-gray-800 mb-2">{{ $index + 1 }}. {{ $pregunta->texto }}</p>
                            @if ($pregunta->imagen)
                                <img src="{{ asset('storage/' . $pregunta->imagen) }}" alt="Imagen de pregunta"
                                    class="mb-3 max-h-48 rounded border">
                            @endif
                        </div>
                        <span
                            class="px-2 py-1 rounded text-xs font-semibold 
                            {{ $fueRespondida ? ($esCorrecta ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') : 'bg-gray-100 text-gray-700' }}">
                            {{ $fueRespondida ? ($esCorrecta ? '✓ Correcta' : '✗ Incorrecta') : '○ No respondida' }}
                        </span>
                    </div>
                    @if ($examen->permitir_revision)
                        <div class="space-y-2 ml-4">
                            @foreach ($pregunta->alternativas as $alt)
                                @php
                                    $fueSeleccionada = $respuesta && $alt->id == $respuesta->alternativa_id;
                                @endphp
                                <div
                                    class="p-2 rounded text-sm flex items-center gap-2
                                    @if ($fueSeleccionada && $alt->es_correcta) bg-green-50 border border-green-300 text-green-700 font-semibold
                                    @elseif($fueSeleccionada) bg-red-50 border border-red-300 text-red-700 font-semibold
                                    @elseif($alt->es_correcta) bg-green-50 border border-green-200 text-green-600 font-semibold
                                    @else text-gray-600 @endif">
                                    <span class="text-lg">
                                        @if($fueSeleccionada)
                                            {{ $alt->es_correcta ? '✓' : '✗' }}
                                        @elseif($alt->es_correcta)
                                            ✓
                                        @else
                                            ○
                                        @endif
                                    </span>
                                    <div class="grow">
                                        {{ $alt->texto }}
                                        @if ($alt->es_correcta && !$fueSeleccionada)
                                            <span class="text-xs">(Respuesta correcta)</span>
                                        @endif
                                    </div>
                                    @if ($alt->imagen)
                                        <img src="{{ asset('storage/' . $alt->imagen) }}" alt="Imagen de alternativa"
                                            class="max-h-16 rounded border">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-blue-50 border border-blue-200 rounded p-4 text-center">
            <p class="text-blue-700">El docente ha configurado este examen para no mostrar las respuestas detalladas.</p>
        </div>
    @endif
@endsection
