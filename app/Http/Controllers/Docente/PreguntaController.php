<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Http\Requests\Docente\StorePreguntaRequest;
use App\Models\Alternativa;
use App\Models\Curso;
use App\Models\Pregunta;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreguntaController extends Controller
{
    public function index(int $curso)
    {
        $curso = Curso::findOrFail($curso);
        $this->authorize('gestionar', $curso);
        $preguntas = Pregunta::where('curso_id', $curso->id)
            ->where('docente_id', Auth::id())
            ->with('alternativas')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('docente.banco-preguntas', compact('curso', 'preguntas'));
    }

    public function create(int $curso)
    {
        $curso = Curso::findOrFail($curso);
        $this->authorize('gestionar', $curso);
        return view('docente.crear-pregunta', compact('curso'));
    }

    public function store(StorePreguntaRequest $request, int $curso)
    {
        $curso = Curso::findOrFail($curso);
        $this->authorize('gestionar', $curso);

        $datosPregunta = [
            'curso_id' => $curso->id,
            'docente_id' => Auth::id(),
            'texto' => $request->texto,
            'dificultad' => $request->dificultad,
            'puntaje' => $request->puntaje,
        ];

        if ($request->hasFile('imagen')) {
            $datosPregunta['imagen'] = $request->file('imagen')->store('preguntas', 'public');
        }

        $pregunta = Pregunta::create($datosPregunta);

        $alternativaCorrectaIndex = $request->alternativa_correcta;

        foreach ($request->alternativas as $index => $alt) {
            $datosAlt = [
                'pregunta_id' => $pregunta->id,
                'texto' => $alt['texto'] ?? null,
                'es_correcta' => ($index == $alternativaCorrectaIndex),
            ];

            if (isset($alt['imagen']) && $alt['imagen']) {
                $datosAlt['imagen'] = $alt['imagen']->store('alternativas', 'public');
            }

            Alternativa::create($datosAlt);
        }

        AuditoriaService::registrar('crear_pregunta', 'Pregunta', $pregunta->id);
        return redirect()->route('docente.banco-preguntas', $curso->id)->with('status', 'Pregunta creada correctamente.');
    }

    public function edit(int $curso, int $pregunta)
    {
        $curso = Curso::findOrFail($curso);
        $pregunta = Pregunta::with('alternativas')->findOrFail($pregunta);
        $this->authorize('update', $pregunta);
        return view('docente.editar-pregunta', compact('curso', 'pregunta'));
    }

    public function update(Request $request, int $curso, int $pregunta)
    {
        $pregunta = Pregunta::findOrFail($pregunta);
        $this->authorize('update', $pregunta);

        $datosAnteriores = $pregunta->only(['texto', 'dificultad', 'puntaje']);

        $pregunta->update($request->only(['texto', 'dificultad', 'puntaje']));

        if ($request->hasFile('imagen')) {
            $pregunta->update(['imagen' => $request->file('imagen')->store('preguntas', 'public')]);
        }

        if ($request->filled('alternativas')) {
            $pregunta->alternativas()->delete();
            $alternativaCorrectaIndex = $request->alternativa_correcta;

            foreach ($request->alternativas as $index => $alt) {
                $datosAlt = [
                    'pregunta_id' => $pregunta->id,
                    'texto' => $alt['texto'] ?? null,
                    'es_correcta' => ($index == $alternativaCorrectaIndex),
                ];
                if (isset($alt['imagen']) && $alt['imagen']) {
                    $datosAlt['imagen'] = $alt['imagen']->store('alternativas', 'public');
                }
                Alternativa::create($datosAlt);
            }
        }

        $datosNuevos = $pregunta->only(['texto', 'dificultad', 'puntaje']);
        AuditoriaService::registrar('actualizar_pregunta', 'Pregunta', $pregunta->id, $datosAnteriores, $datosNuevos);
        return redirect()->route('docente.banco-preguntas', $curso)->with('status', 'Pregunta actualizada correctamente.');
    }

    public function destroy(int $curso, int $pregunta)
    {
        $pregunta = Pregunta::findOrFail($pregunta);
        $this->authorize('delete', $pregunta);
        $pregunta->delete();
        AuditoriaService::registrar('eliminar_pregunta', 'Pregunta', $pregunta->id);
        return redirect()->route('docente.banco-preguntas', $curso)->with('status', 'Pregunta eliminada correctamente.');
    }
}
