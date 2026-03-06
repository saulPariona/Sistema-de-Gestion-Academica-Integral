@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-primary mb-1">Banco de Preguntas</h1>
            <em class="text-gray-600 text-sm">{{ $curso->nombre }}</em>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('docente.preguntas.crear', $curso) }}"
                class="flex items-center gap-2 bg-primary text-accent px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary/90 transition-all shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva Pregunta
            </a>
            <a href="{{ route('docente.curso', $curso) }}"
                class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Curso
            </a>
        </div>
    </div>

    <div class="grid gap-2">
        @forelse ($preguntas as $pregunta)
            <div class="bg-white rounded-sm shadow-lg p-6 border-2 border-gray-400 hover:shadow-2xl transition-all">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex gap-2 mb-3">
                            <span
                                class="px-3 py-1 rounded-sm text-xs font-bold {{ $pregunta->dificultad == 'facil' ? 'bg-green-100 text-green-700 border border-green-300' : ($pregunta->dificultad == 'medio' ? 'bg-yellow-100 text-yellow-700 border border-yellow-300' : 'bg-red-100 text-red-700 border border-red-300') }}">
                                {{ ucfirst($pregunta->dificultad) }}
                            </span>
                            <span
                                class="px-3 py-1 rounded-sm text-xs font-bold bg-primary/10 text-primary border border-primary/30">
                                {{ $pregunta->puntaje }} puntos
                            </span>
                            <span
                                class="px-3 py-1 rounded-sm text-xs font-bold bg-blue-100 text-blue-700 border border-blue-300">
                                {{ $pregunta->alternativas->count() }} alternativas
                            </span>
                        </div>
                        <p class="font-semibold text-lg text-gray-800 mb-3">{{ $pregunta->texto }}</p>
                        @if ($pregunta->imagen)
                            <img src="{{ asset('storage/' . $pregunta->imagen) }}" alt="Imagen"
                                class="mt-2 max-h-40 rounded-sm border-2 border-gray-200 shadow-md">
                        @endif
                        <div class="mt-3 space-y-1.5">
                            @foreach ($pregunta->alternativas as $alt)
                                <div
                                    class="flex items-center gap-2 text-sm p-2 rounded-sm {{ $alt->es_correcta ? 'bg-green-50 border-2 border-green-300 text-green-800 font-semibold' : 'bg-gray-50 border border-gray-200 text-gray-700' }}">
                                    @if ($alt->es_correcta)
                                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @else
                                        <div class="w-4 h-4 rounded-sm border-2 border-gray-400"></div>
                                    @endif
                                    <span>{{ $alt->texto }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex gap-2 ml-4">
                        <a href="{{ route('docente.preguntas.editar', [$curso, $pregunta]) }}"
                            class="flex items-center gap-1 bg-blue-600 text-white px-3 py-2 rounded-sm text-xs font-semibold hover:bg-blue-700 transition-all shadow-md">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Editar
                        </a>
                        <form method="post" action="{{ route('docente.preguntas.eliminar', [$curso, $pregunta]) }}"
                            onsubmit="return confirm('¿Eliminar esta pregunta?')">
                            @csrf
                            @method('DELETE')
                            <button
                                class="flex items-center gap-1 bg-red-600 text-white px-3 py-2 rounded-sm text-xs font-semibold hover:bg-red-700 transition-all shadow-md">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-sm shadow-lg p-12 text-center border-2 border-dashed border-gray-300">
                <svg class="w-8 h-8 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-gray-500 font-medium text-sm">No hay preguntas registradas</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $preguntas->links() }}</div>
@endsection
