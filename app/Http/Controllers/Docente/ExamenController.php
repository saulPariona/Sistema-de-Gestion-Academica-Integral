<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Http\Requests\Docente\StoreExamenRequest;
use App\Http\Requests\Docente\UpdateExamenRequest;
use App\Models\Curso;
use App\Models\Examen;
use App\Models\Intento;
use App\Models\Pregunta;
use App\Events\ExamenPublicado;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamenController extends Controller
{
    public function index(int $curso)
    {
        $curso = Curso::findOrFail($curso);
        $this->authorize('gestionar', $curso);
        $examenes = Examen::where('curso_id', $curso->id)
            ->where('docente_id', Auth::id())
            ->withCount('preguntas')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('docente.examenes', compact('curso', 'examenes'));
    }

    public function create(int $curso)
    {
        $curso = Curso::findOrFail($curso);
        $this->authorize('gestionar', $curso);
        return view('docente.crear-examen', compact('curso'));
    }

    public function store(StoreExamenRequest $request, int $curso)
    {
        $curso = Curso::findOrFail($curso);
        $this->authorize('gestionar', $curso);

        $datos = $request->validated();
        $datos['docente_id'] = Auth::id();
        $datos['curso_id'] = $curso->id;
        $datos['estado'] = 'creado';

        $datos['orden_aleatorio_preguntas'] = $request->has('orden_aleatorio_preguntas');
        $datos['orden_aleatorio_alternativas'] = $request->has('orden_aleatorio_alternativas');
        $datos['mostrar_resultados'] = $request->has('mostrar_resultados');
        $datos['permitir_revision'] = $request->has('permitir_revision');
        $datos['navegacion_libre'] = $request->has('navegacion_libre');

        $examen = Examen::create($datos);
        AuditoriaService::registrar('crear_examen', 'Examen', $examen->id);
        return redirect()->route('docente.examenes.asignar-preguntas', [$curso->id, $examen->id])
            ->with('status', 'Examen creado. Ahora selecciona las preguntas que incluirá.');
    }

    public function edit(int $curso, int $examen)
    {
        $examen = Examen::findOrFail($examen);
        $this->authorize('update', $examen);
        $curso = Curso::findOrFail($curso);
        return view('docente.editar-examen', compact('curso', 'examen'));
    }

    public function update(UpdateExamenRequest $request, int $curso, int $examen)
    {
        $examen = Examen::findOrFail($examen);
        $this->authorize('update', $examen);

        $datosAnteriores = $examen->only(['titulo', 'descripcion', 'fecha_inicio', 'fecha_fin', 'duracion_minutos', 'intentos_permitidos', 'orden_aleatorio_preguntas', 'orden_aleatorio_alternativas', 'mostrar_resultados', 'permitir_revision', 'navegacion_libre']);

        $datos = $request->validated();
        $datos['orden_aleatorio_preguntas'] = $request->has('orden_aleatorio_preguntas');
        $datos['orden_aleatorio_alternativas'] = $request->has('orden_aleatorio_alternativas');
        $datos['mostrar_resultados'] = $request->has('mostrar_resultados');
        $datos['permitir_revision'] = $request->has('permitir_revision');
        $datos['navegacion_libre'] = $request->has('navegacion_libre');

        $examen->update($datos);

        $datosNuevos = $examen->only(['titulo', 'descripcion', 'fecha_inicio', 'fecha_fin', 'duracion_minutos', 'intentos_permitidos', 'orden_aleatorio_preguntas', 'orden_aleatorio_alternativas', 'mostrar_resultados', 'permitir_revision', 'navegacion_libre']);
        AuditoriaService::registrar('actualizar_examen', 'Examen', $examen->id, $datosAnteriores, $datosNuevos);
        return redirect()->route('docente.examenes', $curso)->with('status', 'Examen actualizado correctamente.');
    }

    public function asignarPreguntas(int $curso, int $examen)
    {
        $examen = Examen::with('preguntas')->findOrFail($examen);
        $this->authorize('update', $examen);
        $curso = Curso::findOrFail($curso);
        $preguntasDisponibles = Pregunta::where('curso_id', $curso->id)
            ->where('docente_id', Auth::id())
            ->whereNotIn('id', $examen->preguntas->pluck('id'))
            ->get();

        return view('docente.asignar-preguntas', compact('curso', 'examen', 'preguntasDisponibles'));
    }

    public function guardarAsignacionPreguntas(Request $request, int $curso, int $examen)
    {
        $examen = Examen::findOrFail($examen);
        $this->authorize('update', $examen);

        $request->validate(['preguntas' => 'required|array']);

        $preguntasConOrden = [];
        foreach ($request->preguntas as $index => $preguntaId) {
            $preguntasConOrden[$preguntaId] = ['orden' => $index + 1];
        }

        $examen->preguntas()->syncWithoutDetaching($preguntasConOrden);
        AuditoriaService::registrar('asignar_preguntas', 'Examen', $examen->id);
        return redirect()->route('docente.examenes', $curso)->with('status', 'Preguntas asignadas correctamente.');
    }

    public function publicar(int $curso, int $examen)
    {
        $examen = Examen::with('preguntas')->findOrFail($examen);
        $this->authorize('publicar', $examen);

        if ($examen->preguntas->count() === 0) {
            return redirect()->route('docente.examenes', $curso)
                ->with('error', 'No se puede publicar un examen sin preguntas. Asigna al menos una pregunta primero.');
        }

        $examen->update(['estado' => 'publicado']);
        AuditoriaService::registrar('publicar_examen', 'Examen', $examen->id);
        ExamenPublicado::dispatch($examen);
        return redirect()->route('docente.examenes', $curso)->with('status', 'Examen publicado correctamente.');
    }

    public function cerrar(int $curso, int $examen)
    {
        $examen = Examen::findOrFail($examen);
        $this->authorize('cerrar', $examen);
        $examen->update(['estado' => 'cerrado']);
        AuditoriaService::registrar('cerrar_examen', 'Examen', $examen->id);
        return redirect()->route('docente.examenes', $curso)->with('status', 'Examen cerrado correctamente.');
    }

    public function resultados(int $curso, int $examen)
    {
        $examen = Examen::with(['intentos.estudiante', 'intentos.respuestas.pregunta', 'intentos.respuestas.alternativa'])
            ->findOrFail($examen);
        $this->authorize('verResultados', $examen);
        $curso = Curso::findOrFail($curso);
        return view('docente.resultados-examen', compact('curso', 'examen'));
    }

    public function resultadoEstudiante(int $curso, int $examen, int $intento)
    {
        $examen = Examen::findOrFail($examen);
        $this->authorize('verResultados', $examen);
        $intento = Intento::with(['respuestas.pregunta.alternativas', 'respuestas.alternativa', 'estudiante'])
            ->findOrFail($intento);
        $curso = Curso::findOrFail($curso);
        return view('docente.resultado-estudiante', compact('curso', 'examen', 'intento'));
    }
}
