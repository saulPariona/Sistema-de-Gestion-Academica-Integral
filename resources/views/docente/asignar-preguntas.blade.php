@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-primary mb-1">Asignar Preguntas</h1>
            <em class="text-gray-600 text-sm">{{ $examen->titulo }}</em>
        </div>
        <a href="{{ route('docente.examenes', $curso) }}"
            class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver
        </a>
    </div>

    @if ($examen->preguntas->count())
        <div class="bg-white rounded-sm shadow-lg mb-6 p-6 border-2 border-green-600">
            <div class="flex items-center gap-3 mb-4">
                <div>
                    <h3 class="text-md font-bold text-primary">Preguntas Asignadas</h3>
                    <em class="text-sm text-gray-600 p-2">{{ $examen->preguntas->count() }} preguntas seleccionadas</em>
                </div>
            </div>
            <div class="space-y-1">
                @foreach ($examen->preguntas as $p)
                    <div class="flex justify-between items-center bg-gray-100 p-3 rounded-sm border border-gray-300">
                        <span class="font-medium text-sm text-gray-800">{{ $p->texto }}</span>
                        <div class="flex gap-2">
                            <span
                                class="px-3 py-1 rounded-sm text-xs font-bold bg-primary/10 text-primary">{{ $p->puntaje }}
                                pts</span>
                            <span
                                class="px-3 py-1 rounded-sm text-xs font-bold {{ $p->dificultad == 'facil' ? 'bg-green-100 text-green-700 border border-green-300' : ($p->dificultad == 'medio' ? 'bg-yellow-100 text-yellow-700 border border-yellow-300' : 'bg-red-100 text-red-700 border border-red-300') }}">{{ ucfirst($p->dificultad) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if ($preguntasDisponibles->count())
        <div class="bg-white rounded-sm shadow-lg p-6 border-2 border-gray-400">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 border-gray-200">
                <div>
                    <h3 class="text-md font-bold text-primary">Preguntas Disponibles</h3>
                    <em class="text-sm text-gray-600 p-2">Selecciona las preguntas para este examen</em>
                </div>
            </div>
            <form method="post" action="{{ route('docente.examenes.asignar-preguntas', [$curso, $examen]) }}">
                @csrf
                <div class="space-y-2 mb-6 bg-gray-100 p-1 rounded-sm border border-gray-300">
                    @foreach ($preguntasDisponibles as $p)
                        <label class="flex items-center gap-3 bg-primary/5 p-3 rounded-sm cursor-pointer">
                            <input type="checkbox" name="preguntas[]" value="{{ $p->id }}"
                                class="w-5 h-5 rounded-sm border-2 border-gray-300 text-primary ">
                            <span class="flex-1 font-medium text-gray-800">{{ $p->texto }}</span>
                            <div class="flex gap-2">
                                <span
                                    class="px-3 py-1 rounded-sm text-xs font-bold bg-primary/10 text-primary">{{ $p->puntaje }}
                                    pts</span>
                                <span
                                    class="px-3 py-1 rounded-sm text-xs font-bold {{ $p->dificultad == 'facil' ? 'bg-green-100 text-green-700 border border-green-300' : ($p->dificultad == 'medio' ? 'bg-yellow-100 text-yellow-700 border border-yellow-300' : 'bg-red-100 text-red-700 border border-red-300') }}">{{ ucfirst($p->dificultad) }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
                <button type="submit"
                    class="w-full bg-primary text-accent px-6 py-3 rounded-sm font-semibold flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    Asignar Seleccionadas
                </button>
            </form>
        </div>
    @else
        <div class="bg-yellow-50 border-2 border-yellow-300 rounded-sm p-6 text-center">
            <p class="text-yellow-700 font-semibold mb-2 text-sm">No hay preguntas disponibles para asignar</p>
            <a href="{{ route('docente.preguntas.crear', $curso) }}"
                class="inline-flex items-center gap-2 bg-primary text-accent px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary/90 transition-all shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Crear una nueva pregunta
            </a>
        </div>
    @endif
@endsection
