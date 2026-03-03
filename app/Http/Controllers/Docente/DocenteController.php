<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Http\Requests\Docente\StoreExamenRequest;
use App\Http\Requests\Docente\UpdateExamenRequest;
use App\Http\Requests\Docente\StorePreguntaRequest;
use App\Http\Requests\Docente\StoreObservacionRequest;
use App\Models\Alternativa;
use App\Models\Curso;
use App\Models\Examen;
use App\Models\Intento;
use App\Models\Observacion;
use App\Models\Pregunta;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DocenteController extends Controller
{
    public function dashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $cursos = $user->cursosDocente()->with('periodo')->get();
        return view('docente.dashboard', compact('cursos'));
    }

    public function curso(int $curso)
    {
        $curso = Curso::with(['estudiantes', 'examenes', 'periodo'])->findOrFail($curso);
        $this->authorize('gestionar', $curso);
        return view('docente.curso', compact('curso'));
    }

    public function estudiantesCurso(int $curso)
    {
        $curso = Curso::with('estudiantes')->findOrFail($curso);
        $this->authorize('gestionar', $curso);
        return view('docente.estudiantes', compact('curso'));
    }

    public function bancoPreguntas(int $curso)
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

    public function crearPregunta(int $curso)
    {
        $curso = Curso::findOrFail($curso);
        $this->authorize('gestionar', $curso);
        return view('docente.crear-pregunta', compact('curso'));
    }

    public function guardarPregunta(StorePreguntaRequest $request, int $curso)
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

    public function editarPregunta(int $curso, int $pregunta)
    {
        $curso = Curso::findOrFail($curso);
        $pregunta = Pregunta::with('alternativas')->findOrFail($pregunta);
        $this->authorize('update', $pregunta);
        return view('docente.editar-pregunta', compact('curso', 'pregunta'));
    }

    public function actualizarPregunta(Request $request, int $curso, int $pregunta)
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

    public function eliminarPregunta(int $curso, int $pregunta)
    {
        $pregunta = Pregunta::findOrFail($pregunta);
        $this->authorize('delete', $pregunta);
        $pregunta->delete();
        AuditoriaService::registrar('eliminar_pregunta', 'Pregunta', $pregunta->id);
        return redirect()->route('docente.banco-preguntas', $curso)->with('status', 'Pregunta eliminada correctamente.');
    }

    public function examenes(int $curso)
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

    public function crearExamen(int $curso)
    {
        $curso = Curso::findOrFail($curso);
        $this->authorize('gestionar', $curso);
        return view('docente.crear-examen', compact('curso'));
    }

    public function guardarExamen(StoreExamenRequest $request, int $curso)
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

    public function editarExamen(int $curso, int $examen)
    {
        $examen = Examen::findOrFail($examen);
        $this->authorize('update', $examen);
        $curso = Curso::findOrFail($curso);
        return view('docente.editar-examen', compact('curso', 'examen'));
    }

    public function actualizarExamen(UpdateExamenRequest $request, int $curso, int $examen)
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

    public function publicarExamen(int $curso, int $examen)
    {
        $examen = Examen::with('preguntas')->findOrFail($examen);
        $this->authorize('publicar', $examen);

        if ($examen->preguntas->count() === 0) {
            return redirect()->route('docente.examenes', $curso)
                ->with('error', 'No se puede publicar un examen sin preguntas. Asigna al menos una pregunta primero.');
        }

        $examen->update(['estado' => 'publicado']);
        AuditoriaService::registrar('publicar_examen', 'Examen', $examen->id);
        return redirect()->route('docente.examenes', $curso)->with('status', 'Examen publicado correctamente.');
    }

    public function cerrarExamen(int $curso, int $examen)
    {
        $examen = Examen::findOrFail($examen);
        $this->authorize('cerrar', $examen);
        $examen->update(['estado' => 'cerrado']);
        AuditoriaService::registrar('cerrar_examen', 'Examen', $examen->id);
        return redirect()->route('docente.examenes', $curso)->with('status', 'Examen cerrado correctamente.');
    }

    public function resultadosExamen(int $curso, int $examen)
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

    public function observaciones(int $curso)
    {
        $curso = Curso::findOrFail($curso);
        $this->authorize('gestionar', $curso);
        $observaciones = Observacion::where('curso_id', $curso->id)
            ->where('docente_id', Auth::id())
            ->with('estudiante')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('docente.observaciones', compact('curso', 'observaciones'));
    }

    public function crearObservacion(int $curso)
    {
        $curso = Curso::with('estudiantes')->findOrFail($curso);
        $this->authorize('gestionar', $curso);
        return view('docente.crear-observacion', compact('curso'));
    }

    public function guardarObservacion(StoreObservacionRequest $request, int $curso)
    {
        $observacion = Observacion::create([
            'docente_id' => Auth::id(),
            'estudiante_id' => $request->estudiante_id,
            'curso_id' => $curso,
            'texto' => $request->texto,
        ]);

        AuditoriaService::registrar('crear_observacion', 'Observacion', $observacion->id);
        return redirect()->route('docente.observaciones', $curso)->with('status', 'Observación registrada correctamente.');
    }

    public function exportarNotas(int $curso)
    {
        $curso = Curso::findOrFail($curso);
        $this->authorize('gestionar', $curso);

        $estudiantes = $curso->estudiantes;
        $examenes = Examen::where('curso_id', $curso->id)->get();

        $datos = [];
        foreach ($estudiantes as $estudiante) {
            $fila = [
                'DNI' => $estudiante->dni,
                'Estudiante' => $estudiante->nombreCompleto(),
            ];

            $sumaNotas = 0;
            $contadorExamenes = 0;

            foreach ($examenes as $examen) {
                $intento = Intento::where('examen_id', $examen->id)
                    ->where('estudiante_id', $estudiante->id)
                    ->where('estado', 'finalizado')
                    ->orderBy('puntaje_obtenido', 'desc')
                    ->first();

                $nota = $intento ? $intento->puntaje_obtenido : 0;
                $fila[$examen->titulo] = $nota;
                $sumaNotas += $nota;
                $contadorExamenes++;
            }

            $fila['Promedio'] = $contadorExamenes > 0 ? round($sumaNotas / $contadorExamenes, 2) : 0;
            $datos[] = $fila;
        }

        $filename = "notas_{$curso->nombre}_" . now()->format('Ymd') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($datos) {
            $file = fopen('php://output', 'w');
            if (!empty($datos)) {
                fputcsv($file, array_keys($datos[0]));
                foreach ($datos as $fila) {
                    fputcsv($file, $fila);
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
