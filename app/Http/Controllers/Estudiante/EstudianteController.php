<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use App\Http\Requests\Estudiante\GuardarRespuestaRequest;
use App\Http\Requests\Estudiante\UpdatePerfilRequest;
use App\Models\Curso;
use App\Models\Examen;
use App\Models\Intento;
use App\Models\Respuesta;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EstudianteController extends Controller
{
    public function dashboard()
    {
        $cursos = Curso::whereHas('matriculas', function ($q) {
            $q->where('estudiante_id', Auth::id())->where('estado', 'activa');
        })->with(['docentes', 'periodo'])->get();

        // Cargar exámenes disponibles por curso
        foreach ($cursos as $curso) {
            $curso->examenesDisponibles = Examen::where('curso_id', $curso->id)
                ->where('estado', 'publicado')
                ->where('fecha_inicio', '<=', now())
                ->where('fecha_fin', '>=', now())
                ->get();

            // Contar exámenes no iniciados por el estudiante
            $curso->examenesNuevos = $curso->examenesDisponibles->filter(function ($examen) {
                return !Intento::where('examen_id', $examen->id)
                    ->where('estudiante_id', Auth::id())
                    ->exists();
            })->count();

            // Contar exámenes con intentos pendientes
            $curso->examenesEnProgreso = Intento::whereIn('examen_id', $curso->examenesDisponibles->pluck('id'))
                ->where('estudiante_id', Auth::id())
                ->where('estado', 'en_progreso')
                ->count();
        }

        return view('estudiante.dashboard', compact('cursos'));
    }

    public function curso(int $curso)
    {
        $curso = Curso::with(['docentes', 'periodo'])->findOrFail($curso);

        $matriculado = $curso->estudiantes()->whereKey(Auth::id())->exists();
        if (!$matriculado) {
            abort(403, 'No estás matriculado en este curso.');
        }

        $examenes = Examen::where('curso_id', $curso->id)
            ->where('estado', 'publicado')
            ->orderBy('fecha_inicio')
            ->get();

        return view('estudiante.curso', compact('curso', 'examenes'));
    }

    public function examenesDisponibles(int $curso)
    {
        $curso = Curso::findOrFail($curso);
        $matriculado = $curso->estudiantes()->whereKey(Auth::id())->exists();
        if (!$matriculado) {
            abort(403);
        }

        $examenes = Examen::where('curso_id', $curso->id)
            ->where('estado', 'publicado')
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->with(['intentos' => function ($q) {
                $q->where('estudiante_id', Auth::id())->orderBy('numero_intento', 'desc');
            }])
            ->get();

        return view('estudiante.examenes', compact('curso', 'examenes'));
    }

    public function iniciarExamen(int $curso, int $examen)
    {
        $examen = Examen::with('preguntas.alternativas')->findOrFail($examen);
        $this->authorize('rendir', $examen);

        $intentoActivo = Intento::where('examen_id', $examen->id)
            ->where('estudiante_id', Auth::id())
            ->where('estado', 'en_progreso')
            ->first();

        if ($intentoActivo) {
            return redirect()->route('estudiante.rendir-examen', [$curso, $examen->id, $intentoActivo->id]);
        }

        $intentosRealizados = Intento::where('examen_id', $examen->id)
            ->where('estudiante_id', Auth::id())
            ->count();

        if ($intentosRealizados >= $examen->intentos_permitidos) {
            return redirect()->route('estudiante.examenes', $curso)
                ->with('error', 'Ya has agotado todos los intentos permitidos para este examen.');
        }

        $numeroIntento = $intentosRealizados + 1;

        $intento = Intento::create([
            'examen_id' => $examen->id,
            'estudiante_id' => Auth::id(),
            'numero_intento' => $numeroIntento,
            'inicio' => now(),
            'estado' => 'en_progreso',
        ]);

        AuditoriaService::registrar('iniciar_examen', 'Intento', $intento->id);
        return redirect()->route('estudiante.rendir-examen', [$curso, $examen->id, $intento->id]);
    }

    public function rendirExamen(int $curso, int $examen, int $intento)
    {
        $intento = Intento::with('respuestas')->findOrFail($intento);
        $examen = Examen::with('preguntas.alternativas')->findOrFail($examen);

        if ($intento->estudiante_id !== Auth::id()) {
            abort(403);
        }

        if (!$intento->estaEnProgreso()) {
            return redirect()->route('estudiante.resultado-examen', [$curso, $examen->id, $intento->id]);
        }

        $tiempoRestante = $intento->tiempoRestante();
        if ($tiempoRestante <= 0) {
            return $this->finalizarExamen($curso, $examen->id, $intento->id);
        }

        $preguntas = $examen->preguntas;
        if ($examen->orden_aleatorio_preguntas) {
            $preguntas = $preguntas->shuffle();
        }

        foreach ($preguntas as $pregunta) {
            if ($examen->orden_aleatorio_alternativas) {
                $pregunta->setRelation('alternativas', $pregunta->alternativas->shuffle());
            }
        }

        $respuestasGuardadas = $intento->respuestas->pluck('alternativa_id', 'pregunta_id');

        return view('estudiante.rendir-examen', compact('curso', 'examen', 'intento', 'preguntas', 'tiempoRestante', 'respuestasGuardadas'));
    }

    public function guardarRespuesta(GuardarRespuestaRequest $request, int $curso, int $examen, int $intento)
    {
        $intento = Intento::findOrFail($intento);

        if ($intento->estudiante_id !== Auth::id() || !$intento->estaEnProgreso()) {
            abort(403);
        }

        Respuesta::updateOrCreate(
            [
                'intento_id' => $intento->id,
                'pregunta_id' => $request->pregunta_id,
            ],
            [
                'alternativa_id' => $request->alternativa_id,
            ]
        );

        return response()->json(['status' => 'guardado']);
    }

    public function finalizarExamen(int $curso, int $examen, int $intento)
    {
        $intento = Intento::with(['respuestas.alternativa', 'examen.preguntas'])->findOrFail($intento);

        if ($intento->estudiante_id !== Auth::id()) {
            abort(403);
        }

        if ($intento->estaEnProgreso()) {
            $puntaje = $intento->calcularPuntaje();
            $intento->update([
                'fin' => now(),
                'puntaje_obtenido' => $puntaje,
                'estado' => 'finalizado',
            ]);
            AuditoriaService::registrar('finalizar_examen', 'Intento', $intento->id);
        }

        return redirect()->route('estudiante.resultado-examen', [$curso, $examen, $intento->id]);
    }

    public function resultadoExamen(int $curso, int $examen, int $intento)
    {
        $intento = Intento::with(['respuestas.pregunta.alternativas', 'respuestas.alternativa', 'examen'])
            ->findOrFail($intento);
        $examen = Examen::with('preguntas.alternativas')->findOrFail($examen);
        $curso = Curso::findOrFail($curso);

        if ($intento->estudiante_id !== Auth::id()) {
            abort(403);
        }

        return view('estudiante.resultado-examen', compact('curso', 'examen', 'intento'));
    }

    public function calificaciones()
    {
        $cursos = Curso::whereHas('matriculas', function ($q) {
            $q->where('estudiante_id', Auth::id())->where('estado', 'activa');
        })->get();

        $calificacionesPorCurso = [];
        foreach ($cursos as $curso) {
            $examenes = Examen::where('curso_id', $curso->id)->get();
            $notas = [];
            $suma = 0;
            $count = 0;

            foreach ($examenes as $examen) {
                $intento = Intento::where('examen_id', $examen->id)
                    ->where('estudiante_id', Auth::id())
                    ->where('estado', 'finalizado')
                    ->orderBy('puntaje_obtenido', 'desc')
                    ->first();

                $nota = $intento ? $intento->puntaje_obtenido : null;
                $notas[] = [
                    'examen' => $examen->titulo,
                    'nota' => $nota,
                ];
                if ($nota !== null) {
                    $suma += $nota;
                    $count++;
                }
            }

            $calificacionesPorCurso[] = [
                'curso' => $curso,
                'notas' => $notas,
                'promedio' => $count > 0 ? round($suma / $count, 2) : null,
            ];
        }

        return view('estudiante.calificaciones', compact('calificacionesPorCurso'));
    }

    public function perfil()
    {
        $user = Auth::user();
        return view('estudiante.perfil', compact('user'));
    }

    public function actualizarPerfil(UpdatePerfilRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $datos = $request->only(['telefono', 'direccion']);

        if ($request->hasFile('foto_perfil')) {
            $datos['foto_perfil'] = $request->file('foto_perfil')->store('fotos', 'public');
        }

        if ($request->filled('password')) {
            $datos['password'] = Hash::make($request->password);
        }

        $user->update($datos);
        AuditoriaService::registrar('actualizar_perfil', 'User', $user->id);
        return back()->with('status', 'Perfil actualizado correctamente.');
    }
}
