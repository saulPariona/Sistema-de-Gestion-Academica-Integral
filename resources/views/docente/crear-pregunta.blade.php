@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Nueva Pregunta - {{ $curso->nombre }}</h1>
        <a href="{{ route('docente.banco-preguntas', $curso) }}" class="text-blue-600 hover:underline text-sm">←
            Volver</a>
    </div>

    <div class="bg-white rounded border border-gray-200 p-6 max-w-2xl">
        <form method="post" action="{{ route('docente.preguntas.guardar', $curso) }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Texto de la Pregunta</label>
                <textarea name="texto" rows="3"
                    class="w-full p-2 text-sm border border-gray-300 rounded">{{ old('texto') }}</textarea>
                @error('texto')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Imagen (opcional)</label>
                <input type="file" name="imagen" accept="image/*"
                    class="w-full p-2 text-sm border border-gray-300 rounded">
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Dificultad</label>
                    <select name="dificultad" class="w-full p-2 text-sm border border-gray-300 rounded">
                        <option value="facil" {{ old('dificultad') == 'facil' ? 'selected' : '' }}>Fácil</option>
                        <option value="medio" {{ old('dificultad', 'medio') == 'medio' ? 'selected' : '' }}>Medio
                        </option>
                        <option value="dificil" {{ old('dificultad') == 'dificil' ? 'selected' : '' }}>Difícil</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Puntaje</label>
                    <input type="number" name="puntaje" value="{{ old('puntaje', 1) }}" min="0.5" step="0.5"
                        class="w-full p-2 text-sm border border-gray-300 rounded">
                    @error('puntaje')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alternativas (mínimo 4, marcar la
                    correcta)</label>
                @error('alternativas')
                    <p class="text-red-600 text-xs mb-2">{{ $message }}</p>
                @enderror
                @error('alternativa_correcta')
                    <p class="text-red-600 text-xs mb-2">{{ $message }}</p>
                @enderror
                <div id="alternativas-container">
                    @for ($i = 0; $i < 4; $i++)
                        <div class="flex gap-2 items-center mb-2">
                            <input type="radio" name="alternativa_correcta" value="{{ $i }}"
                                {{ old('alternativa_correcta') == $i ? 'checked' : '' }}>
                            <input type="text" name="alternativas[{{ $i }}][texto]"
                                value="{{ old("alternativas.{$i}.texto") }}" placeholder="Alternativa {{ $i + 1 }}"
                                class="grow p-2 text-sm border border-gray-300 rounded">
                        </div>
                    @endfor
                </div>
                <button type="button" onclick="agregarAlternativa()"
                    class="text-blue-600 text-xs hover:underline mt-1">+ Agregar alternativa</button>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white px-4 py-2 rounded font-semibold hover:bg-blue-700 text-sm">Guardar
                Pregunta</button>
        </form>
    </div>

    <script>
        let altIndex = 4;

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
