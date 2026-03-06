<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Examen;
use App\Models\Intento;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $cursos = Curso::whereHas('matriculas', function ($q) {
            $q->where('estudiante_id', Auth::id())->where('estado', 'activa');
        })->with(['docentes', 'periodo'])->get();

        foreach ($cursos as $curso) {
            $curso->examenesDisponibles = Examen::where('curso_id', $curso->id)
                ->where('estado', 'publicado')
                ->where('fecha_inicio', '<=', now())
                ->where('fecha_fin', '>=', now())
                ->get();

            $curso->examenesNuevos = $curso->examenesDisponibles->filter(function ($examen) {
                return !Intento::where('examen_id', $examen->id)->where('estudiante_id', Auth::id())->exists();
            })->count();

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
}
