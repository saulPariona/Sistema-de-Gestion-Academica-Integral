@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-primary mb-1">Editar Pregunta</h1>
            <em class="text-gray-600 text-sm">{{ $curso->nombre }}</em>
        </div>
        <a href="{{ route('docente.banco-preguntas', $curso) }}"
            class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver
        </a>
    </div>

    <div class="bg-white rounded-sm shadow-lg p-8 max-w-4xl mx-auto border-2 border-gray-400">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 border-gray-200">
            <div class="bg-primary/10 p-3 rounded-sm">
                <svg class="w-6 h-6 text-primary" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-primary">Modificar Pregunta</h2>
                <p class="text-sm text-gray-600">Actualiza los datos de la pregunta</p>
            </div>
        </div>

        <form method="post" action="{{ route('docente.preguntas.actualizar', [$curso, $pregunta]) }}"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Texto de la Pregunta</label>
                <textarea name="texto" rows="3" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm">{{ old('texto', $pregunta->texto) }}</textarea>
                @error('texto')
                    <p class="text-red-600 text-xs mt-1 flex items-center gap-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Imagen (opcional)</label>
                @if ($pregunta->imagen)
                    <img src="{{ asset('storage/' . $pregunta->imagen) }}" alt="Imagen actual"
                        class="mb-2 max-h-24 rounded-sm border-2 border-gray-200">
                @endif
                <input type="file" name="imagen" accept="image/*"
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm">
            </div>

            <div class="grid grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Dificultad</label>
                    <select name="dificultad" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm">
                        <option value="facil" {{ old('dificultad', $pregunta->dificultad) == 'facil' ? 'selected' : '' }}>
                            Fácil</option>
                        <option value="medio" {{ old('dificultad', $pregunta->dificultad) == 'medio' ? 'selected' : '' }}>
                            Medio</option>
                        <option value="dificil"
                            {{ old('dificultad', $pregunta->dificultad) == 'dificil' ? 'selected' : '' }}>Difícil</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Puntaje</label>
                    <input type="number" name="puntaje" value="{{ old('puntaje', $pregunta->puntaje) }}" min="0.5"
                        step="0.5" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Alternativas</label>
                <div id="alternativas-container" class="space-y-2">
                    @foreach ($pregunta->alternativas as $i => $alt)
                        <div class="flex gap-2 items-center">
                            <input type="radio" name="alternativa_correcta" value="{{ $i }}"
                                {{ $alt->es_correcta ? 'checked' : '' }}
                                class="w-5 h-5 text-primary focus:ring-2 focus:ring-primary/20">
                            <input type="text" name="alternativas[{{ $i }}][texto]"
                                value="{{ old("alternativas.{$i}.texto", $alt->texto) }}"
                                class="grow px-4 py-2.5 border-2 border-gray-200 rounded-sm">
                            @if ($i >= 4)
                                <button type="button" onclick="this.parentElement.remove()"
                                    class="text-red-600 hover:text-red-700 p-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="agregarAlternativa()"
                    class="flex items-center gap-1 text-primary text-xs font-semibold hover:text-primary/80 mt-2">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Agregar alternativa
                </button>
            </div>

            <div class="flex justify-center gap-3">
                <button type="submit"
                    class="bg-primary text-accent px-6 py-3 rounded-sm font-bold hover:bg-primary/90 transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Actualizar Pregunta
                </button>
            </div>
        </form>
    </div>

    <script>
        let altIndex = {{ $pregunta->alternativas->count() }};

        function agregarAlternativa() {
            const container = document.getElementById('alternativas-container');
            const div = document.createElement('div');
            div.className = 'flex gap-2 items-center';
            div.innerHTML = `
                <input type="radio" name="alternativa_correcta" value="${altIndex}"
                    class="w-5 h-5 text-primary focus:ring-2 focus:ring-primary/20">
                <input type="text" name="alternativas[${altIndex}][texto]" placeholder="Alternativa ${altIndex + 1}"
                    class="grow px-4 py-2.5 border-2 border-gray-200 rounded-sm focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-700 p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            `;
            container.appendChild(div);
            altIndex++;
        }
    </script>
@endsection
