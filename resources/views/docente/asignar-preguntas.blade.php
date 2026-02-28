@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-primary mb-1">Asignar Preguntas</h1>
            <p class="text-gray-600">{{ $examen->titulo }}</p>
        </div>
        <a href="{{ route('docente.examenes', $curso) }}"
            class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver
        </a>
    </div>

    @if ($examen->preguntas->count())
        <div class="bg-white rounded-xl shadow-lg mb-6 p-6 border-2 border-green-200">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-primary">Preguntas Asignadas</h3>
                    <p class="text-sm text-gray-600">{{ $examen->preguntas->count() }} preguntas seleccionadas</p>
                </div>
            </div>
            <div class="space-y-2">
                @foreach ($examen->preguntas as $p)
                    <div class="flex justify-between items-center bg-green-50 p-3 rounded-lg border border-green-200">
                        <span class="font-medium text-gray-800">{{ $p->texto }}</span>
                        <div class="flex gap-2">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-primary/10 text-primary border border-primary/30">{{ $p->puntaje }} pts</span>
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $p->dificultad == 'facil' ? 'bg-green-100 text-green-700 border border-green-300' : ($p->dificultad == 'medio' ? 'bg-yellow-100 text-yellow-700 border border-yellow-300' : 'bg-red-100 text-red-700 border border-red-300') }}">{{ ucfirst($p->dificultad) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if ($preguntasDisponibles->count())
        <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-primary/20">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 border-primary/20">
                <div class="bg-primary/10 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-primary" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-primary">Preguntas Disponibles</h3>
                    <p class="text-sm text-gray-600">Selecciona las preguntas para este examen</p>
                </div>
            </div>
            <form method="post" action="{{ route('docente.examenes.asignar-preguntas', [$curso, $examen]) }}">
                @csrf
                <div class="space-y-2 mb-6">
                    @foreach ($preguntasDisponibles as $p)
                        <label class="flex items-center gap-3 bg-primary/5 p-3 rounded-lg cursor-pointer hover:bg-primary/10 transition-all border-2 border-transparent hover:border-primary/30">
                            <input type="checkbox" name="preguntas[]" value="{{ $p->id }}"
                                class="w-5 h-5 rounded border-2 border-gray-300 text-primary focus:ring-2 focus:ring-primary/20">
                            <span class="flex-1 font-medium text-gray-800">{{ $p->texto }}</span>
                            <div class="flex gap-2">
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-primary/10 text-primary border border-primary/30">{{ $p->puntaje }} pts</span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $p->dificultad == 'facil' ? 'bg-green-100 text-green-700 border border-green-300' : ($p->dificultad == 'medio' ? 'bg-yellow-100 text-yellow-700 border border-yellow-300' : 'bg-red-100 text-red-700 border border-red-300') }}">{{ ucfirst($p->dificultad) }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
                <button type="submit"
                    class="w-full bg-primary text-accent px-6 py-3 rounded-lg font-bold hover:bg-primary/90 transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Asignar Seleccionadas
                </button>
            </form>
        </div>
    @else
        <div class="bg-yellow-50 border-2 border-yellow-300 rounded-xl p-6 text-center">
            <svg class="w-12 h-12 text-yellow-600 mx-auto mb-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <p class="text-yellow-700 font-semibold mb-2">No hay preguntas disponibles para asignar</p>
            <a href="{{ route('docente.preguntas.crear', $curso) }}"
                class="inline-flex items-center gap-2 text-yellow-700 underline font-semibold hover:text-yellow-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Crear una nueva pregunta
            </a>
        </div>
    @endif
@endsection
