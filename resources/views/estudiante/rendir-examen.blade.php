@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-4">
        <div>
            <h1 class="text-xl font-bold text-primary mb-1">{{ $examen->titulo }}</h1>
            <em class="text-gray-600 text-sm p-2">Intento N° {{ $intento->numero_intento }}</em>
        </div>
        <div id="temporizador"
            class="bg-red-600 text-white px-4 py-2 rounded-sm font-mono text-lg font-semibold shadow-md"></div>
    </div>

    <form id="examen-form" method="post"
        action="{{ route('estudiante.finalizar-examen', [$curso, $examen->id, $intento->id]) }}">
        @csrf
    </form>

    <div class="grid gap-2">
        @foreach ($preguntas as $index => $pregunta)
            <div class="bg-white rounded-sm shadow-lg border-2 border-gray-400 p-5" id="pregunta-{{ $pregunta->id }}">
                <p class="font-bold text-sm text-gray-800 mb-3">{{ $index + 1 }}. {{ $pregunta->texto }}</p>

                @if ($pregunta->imagen)
                    <img src="{{ asset('storage/' . $pregunta->imagen) }}" alt="Imagen"
                        class="mb-3 max-h-48 rounded-sm border border-gray-200">
                @endif

                <div class="space-y-2 ml-4">
                    @foreach ($pregunta->alternativas as $alt)
                        <label
                            class="flex items-center gap-3 p-2 rounded-sm cursor-pointer bg-gray-50 border-gray-100 hover:bg-gray-200 text-sm alternativa-label border hover:border-gray-300 transition-all"
                            data-pregunta="{{ $pregunta->id }}" data-alternativa="{{ $alt->id }}">
                            <input type="radio" name="pregunta_{{ $pregunta->id }}" value="{{ $alt->id }}"
                                {{ isset($respuestasGuardadas[$pregunta->id]) && $respuestasGuardadas[$pregunta->id] == $alt->id ? 'checked' : '' }}
                                onchange="guardarRespuesta({{ $pregunta->id }}, {{ $alt->id }})"
                                class="text-primary">
                            <span>{{ $alt->texto }}</span>
                            @if ($alt->imagen)
                                <img src="{{ asset('storage/' . $alt->imagen) }}" alt="Imagen"
                                    class="max-h-16 rounded-sm border">
                            @endif
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6 flex justify-between items-center bg-white rounded-sm shadow-lg p-4 border-2 border-gray-400">
        <p class="text-sm text-gray-600 font-semibold">Respondidas: <span id="contador-respuestas" class="text-primary">{{ $respuestasGuardadas->count() }}</span> / {{ $preguntas->count() }}</p>
        <button onclick="confirmarFinalizar()"
            class="flex items-center gap-2 bg-red-600 text-white px-4 py-2.5 rounded-sm text-sm font-semibold hover:bg-red-700">
            Finalizar Examen
        </button>
    </div>

    <script>
        let tiempoRestante = {{ $tiempoRestante }};
        const totalPreguntas = {{ $preguntas->count() }};
        let respondidas = {{ $respuestasGuardadas->count() }};

        function actualizarTemporizador() {
            if (tiempoRestante <= 0) {
                document.getElementById('examen-form').submit();
                return;
            }
            const minutos = Math.floor(tiempoRestante / 60);
            const segundos = tiempoRestante % 60;
            document.getElementById('temporizador').textContent =
                String(minutos).padStart(2, '0') + ':' + String(segundos).padStart(2, '0');

            if (tiempoRestante <= 60) {
                document.getElementById('temporizador').classList.add('animate-pulse');
            }

            tiempoRestante--;
        }

        actualizarTemporizador();
        setInterval(actualizarTemporizador, 1000);

        function guardarRespuesta(preguntaId, alternativaId) {
            fetch('{{ route("estudiante.guardar-respuesta", [$curso, $examen->id, $intento->id]) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    pregunta_id: preguntaId,
                    alternativa_id: alternativaId
                })
            }).then(response => {
                if (response.ok) {
                    const preguntaDiv = document.getElementById('pregunta-' + preguntaId);
                    if (!preguntaDiv.dataset.respondida) {
                        respondidas++;
                        preguntaDiv.dataset.respondida = '1';
                        document.getElementById('contador-respuestas').textContent = respondidas;
                    }
                }
            });
        }

        function confirmarFinalizar() {
            if (respondidas < totalPreguntas) {
                if (!confirm('Aun tienes preguntas sin responder. Deseas finalizar el examen?')) {
                    return;
                }
            } else {
                if (!confirm('Estas seguro de finalizar el examen?')) {
                    return;
                }
            }
            document.getElementById('examen-form').submit();
        }

        window.addEventListener('beforeunload', function(e) {
            e.preventDefault();
            e.returnValue = '';
        });
    </script>
@endsection
