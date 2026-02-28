@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Editar Pregunta - {{ $curso->nombre }}</h1>
        <a href="{{ route('docente.banco-preguntas', $curso) }}" class="text-blue-600 hover:underline text-sm">←
            Volver</a>
    </div>

    <div class="bg-white rounded border border-gray-200 p-6 max-w-2xl">
        <form method="post" action="{{ route('docente.preguntas.actualizar', [$curso, $pregunta]) }}"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Texto de la Pregunta</label>
                <textarea name="texto" rows="3"
                    class="w-full p-2 text-sm border border-gray-300 rounded">{{ old('texto', $pregunta->texto) }}</textarea>
                @error('texto')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Imagen (opcional)</label>
                @if ($pregunta->imagen)
                    <img src="{{ asset('storage/' . $pregunta->imagen) }}" alt="Imagen actual"
                        class="mb-2 max-h-24 rounded">
                @endif
                <input type="file" name="imagen" accept="image/*"
                    class="w-full p-2 text-sm border border-gray-300 rounded">
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Dificultad</label>
                    <select name="dificultad" class="w-full p-2 text-sm border border-gray-300 rounded">
                        <option value="facil"
                            {{ old('dificultad', $pregunta->dificultad) == 'facil' ? 'selected' : '' }}>Fácil</option>
                        <option value="medio"
                            {{ old('dificultad', $pregunta->dificultad) == 'medio' ? 'selected' : '' }}>Medio</option>
                        <option value="dificil"
                            {{ old('dificultad', $pregunta->dificultad) == 'dificil' ? 'selected' : '' }}>Difícil</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Puntaje</label>
                    <input type="number" name="puntaje" value="{{ old('puntaje', $pregunta->puntaje) }}" min="0.5"
                        step="0.5" class="w-full p-2 text-sm border border-gray-300 rounded">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alternativas</label>
                <div id="alternativas-container">
                    @foreach ($pregunta->alternativas as $i => $alt)
                        <div class="flex gap-2 items-center mb-2">
                            <input type="radio" name="alternativa_correcta" value="{{ $i }}"
                                {{ $alt->es_correcta ? 'checked' : '' }}>
                            <input type="text" name="alternativas[{{ $i }}][texto]"
                                value="{{ old("alternativas.{$i}.texto", $alt->texto) }}"
                                class="grow p-2 text-sm border border-gray-300 rounded">
                            @if ($i >= 4)
                                <button type="button" onclick="this.parentElement.remove()"
                                    class="text-red-600 text-xs">✕</button>
                            @endif
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="agregarAlternativa()"
                    class="text-blue-600 text-xs hover:underline mt-1">+ Agregar alternativa</button>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white px-4 py-2 rounded font-semibold hover:bg-blue-700 text-sm">Actualizar
                Pregunta</button>
        </form>
    </div>

    <script>
        let altIndex = {{ $pregunta->alternativas->count() }};

        function agregarAlternativa() {
            const container = document.getElementById('alternativas-container');
            const div = document.createElement('div');
            div.className = 'flex gap-2 items-center mb-2';
            div.innerHTML = `
                <input type="radio" name="alternativa_correcta" value="${altIndex}">
                <input type="text" name="alternativas[${altIndex}][texto]" placeholder="Alternativa ${altIndex + 1}"
                    class="grow p-2 text-sm border border-gray-300 rounded">
                <button type="button" onclick="this.parentElement.remove()" class="text-red-600 text-xs">✕</button>
            `;
            container.appendChild(div);
            altIndex++;
        }
    </script>
@endsection
