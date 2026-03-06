<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Examen;
use App\Models\Intento;
use App\Models\Matricula;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EstudianteApiController extends Controller
{
    public function cursos(Request $request): JsonResponse
    {
        $cursos = Curso::whereHas('matriculas', function ($q) use ($request) {
            $q->where('estudiante_id', $request->user()->id)
              ->where('estado', 'activa');
        })->with(['docentes:id,nombres,apellidos', 'periodo:id,nombre'])->get();

        return response()->json($cursos);
    }

    public function examenes(Request $request, int $cursoId): JsonResponse
    {
        $curso = Curso::findOrFail($cursoId);

        $matriculado = Matricula::where('estudiante_id', $request->user()->id)
            ->where('curso_id', $cursoId)
            ->where('estado', 'activa')
            ->exists();

        if (! $matriculado) {
            return response()->json(['message' => 'No estás matriculado en este curso.'], 403);
        }

        $examenes = Examen::where('curso_id', $cursoId)
            ->where('estado', 'publicado')
            ->select('id', 'titulo', 'descripcion', 'puntaje_total', 'tiempo_limite', 'fecha_inicio', 'fecha_fin', 'intentos_permitidos')
            ->get();

        return response()->json($examenes);
    }

    public function calificaciones(Request $request): JsonResponse
    {
        $intentos = Intento::where('estudiante_id', $request->user()->id)
            ->where('estado', 'finalizado')
            ->with(['examen:id,titulo,puntaje_total,curso_id', 'examen.curso:id,nombre'])
            ->orderByDesc('fin')
            ->get()
            ->map(fn ($intento) => [
                'id' => $intento->id,
                'examen' => $intento->examen->titulo,
                'curso' => $intento->examen->curso->nombre,
                'puntaje_obtenido' => $intento->puntaje_obtenido,
                'puntaje_total' => $intento->examen->puntaje_total,
                'nota_base_20' => $intento->examen->puntaje_total > 0
                    ? round(($intento->puntaje_obtenido / $intento->examen->puntaje_total) * 20, 2)
                    : 0,
                'intento' => $intento->numero_intento,
                'fecha' => $intento->fin?->format('d/m/Y H:i'),
            ]);

        return response()->json($intentos);
    }
}
