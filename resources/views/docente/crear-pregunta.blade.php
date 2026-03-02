@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-primary mb-1">Nueva Pregunta</h1>
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
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-primary">Crear Pregunta</h2>
                <p class="text-sm text-gray-600">Complete los datos de la nueva pregunta</p>
            </div>
        </div>

        <form method="post" action="{{ route('docente.preguntas.guardar', $curso) }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Texto de la Pregunta</label>
                <textarea name="texto" rows="3" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm">{{ old('texto') }}</textarea>
                @error('texto')
                    <p class="text-red-600 text-xs mt-1 flex items-center gap-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-2">Imagen (opcional)</label>
                <input type="file" name="imagen" accept="image/*"
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm">
            </div>

            <div class="grid grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Dificultad</label>
                    <select name="dificultad" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm">
                        <option value="facil" {{ old('dificultad') == 'facil' ? 'selected' : '' }}>Fácil</option>
                        <option value="medio" {{ old('dificultad', 'medio') == 'medio' ? 'selected' : '' }}>Medio</option>
                        <option value="dificil" {{ old('dificultad') == 'dificil' ? 'selected' : '' }}>Difícil</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Puntaje</label>
                    <input type="number" name="puntaje" value="{{ old('puntaje', 1) }}" min="0.5" step="0.5"
                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm">
                    @error('puntaje')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Alternativas (mínimo 4, marcar la
                    correcta)</label>
                @error('alternativas')
                    <p class="text-red-600 text-xs mb-2 flex items-center gap-1">
                        {{ $message }}
                    </p>
                @enderror
                @error('alternativa_correcta')
                    <p class="text-red-600 text-xs mb-2 flex items-center gap-1">
                        {{ $message }}
                    </p>
                @enderror
                <div id="alternativas-container" class="space-y-2">
                    @for ($i = 0; $i < 4; $i++)
                        <div class="flex gap-2 items-center">
                            <input type="radio" name="alternativa_correcta" value="{{ $i }}"
                                {{ old('alternativa_correcta') == $i ? 'checked' : '' }} class="w-5 h-5 text-primary">
                            <input type="text" name="alternativas[{{ $i }}][texto]"
                                value="{{ old("alternativas.{$i}.texto") }}" placeholder="Alternativa {{ $i + 1 }}"
                                class="grow px-4 py-2.5 border-2 border-gray-200 rounded-sm">
                        </div>
                    @endfor
                </div>
                <button type="button" onclick="agregarAlternativa()"
                    class="flex items-center gap-1 text-primary text-xs font-semibold hover:text-primary/80 mt-2">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Agregar alternativa
                </button>
            </div>

            <div class="flex gap-3 justify-center">
                <button type="submit"
                    class="bg-primary text-accent px-6 py-3 rounded-sm font-bold hover:bg-primary/90 transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Guardar Pregunta
                </button>
            </div>
        </form>
    </div>

    <script>
        let altIndex = 4;

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
