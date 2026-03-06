<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Examen;
use App\Models\Intento;
use Illuminate\Support\Facades\Auth;

class CalificacionController extends Controller
{
    public function __invoke()
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

                $nota = null;
                if ($intento && $examen->puntaje_total > 0) {
                    $nota = round(($intento->puntaje_obtenido / $examen->puntaje_total) * 20, 2);
                }

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
}
