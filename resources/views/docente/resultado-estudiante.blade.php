@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-primary mb-1">Detalle del Intento</h1>
            <p class="text-gray-600">{{ $intento->estudiante->nombreCompleto() }}</p>
        </div>
        <a href="{{ route('docente.examenes.resultados', [$curso, $examen]) }}"
            class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Resultados
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg mb-6 p-6 border-2 border-primary/20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="flex items-center gap-3">
                <div class="bg-primary/10 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Examen</p>
                    <p class="font-bold text-gray-800">{{ $examen->titulo }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Intento</p>
                    <p class="font-bold text-gray-800">#{{ $intento->numero_intento }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Puntaje</p>
                    <p class="font-bold text-gray-800">{{ $intento->puntaje_obtenido ?? '-' }} / {{ $examen->puntaje_total }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="{{ $intento->estado == 'finalizado' ? 'bg-green-100' : 'bg-yellow-100' }} p-3 rounded-lg">
                    <svg class="w-6 h-6 {{ $intento->estado == 'finalizado' ? 'text-green-600' : 'text-yellow-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Estado</p>
                    <p class="font-bold text-gray-800">{{ ucfirst($intento->estado) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        @foreach ($intento->respuestas as $index => $respuesta)
            <div class="bg-white rounded-xl shadow-lg p-6 border-2 {{ $respuesta->esCorrecta() ? 'border-green-300' : 'border-red-300' }} hover:shadow-2xl transition-all">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-start gap-3 flex-1">
                        <div class="{{ $respuesta->esCorrecta() ? 'bg-green-100' : 'bg-red-100' }} rounded-full p-2 mt-1">
                            @if ($respuesta->esCorrecta())
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </div>
                        <p class="font-semibold text-lg text-gray-800"><span class="text-primary">{{ $index + 1 }}.</span> {{ $respuesta->pregunta->texto }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $respuesta->esCorrecta() ? 'bg-green-100 text-green-700 border-green-300' : 'bg-red-100 text-red-700 border-red-300' }}">
                        {{ $respuesta->esCorrecta() ? 'Correcta' : 'Incorrecta' }}
                    </span>
                </div>
                <div class="space-y-2 ml-10">
                    @foreach ($respuesta->pregunta->alternativas as $alt)
                        <div class="flex items-center gap-3 text-sm p-3 rounded-lg transition-all
                            @if ($alt->id == $respuesta->alternativa_id && $alt->es_correcta) 
                                bg-green-50 border-2 border-green-300 text-green-800 font-semibold
                            @elseif($alt->id == $respuesta->alternativa_id && !$alt->es_correcta) 
                            
                            @elseif($alt->es_correcta) 
                               
                            @else 
                                
                            @endif">
                            @if ($alt->id == $respuesta->alternativa_id)
                                @if ($alt->es_correcta)
                                    <svg class="w-5 h-5 text-green-600 " fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-red-600 " fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                            @elseif ($alt->es_correcta)
                                <svg class="w-5 h-5 text-green-600 " fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <div class="w-5 h-5 rounded-full border-2 border-gray-400 "></div>
                            @endif
                            <span>{{ $alt->texto }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endsection
