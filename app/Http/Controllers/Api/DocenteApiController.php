<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Examen;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DocenteApiController extends Controller
{
    public function cursos(Request $request): JsonResponse
    {
        $cursos = Curso::whereHas('docentes', function ($q) use ($request) {
            $q->where('users.id', $request->user()->id);
        })->with(['periodo:id,nombre'])
          ->withCount('estudiantes')
          ->get();

        return response()->json($cursos);
    }

    public function examenes(Request $request, int $cursoId): JsonResponse
    {
        $curso = Curso::findOrFail($cursoId);

        $esDocente = $curso->docentes()->whereKey($request->user()->id)->exists();
        if (! $esDocente) {
            return response()->json(['message' => 'No eres docente de este curso.'], 403);
        }

        $examenes = Examen::where('curso_id', $cursoId)
            ->where('docente_id', $request->user()->id)
            ->withCount('intentos')
            ->get();

        return response()->json($examenes);
    }

    public function resultados(Request $request, int $cursoId, int $examenId): JsonResponse
    {
        $examen = Examen::with(['intentos' => function ($q) {
            $q->where('estado', 'finalizado')
              ->with('estudiante:id,nombres,apellidos,dni')
              ->orderByDesc('puntaje_obtenido');
        }])->findOrFail($examenId);

        if ($examen->docente_id !== $request->user()->id) {
            return response()->json(['message' => 'No tienes acceso a estos resultados.'], 403);
        }

        return response()->json([
            'examen' => $examen->titulo,
            'total_intentos' => $examen->intentos->count(),
            'promedio' => round($examen->intentos->avg('puntaje_obtenido'), 2),
            'resultados' => $examen->intentos->map(fn ($i) => [
                'estudiante' => $i->estudiante->nombres . ' ' . $i->estudiante->apellidos,
                'dni' => $i->estudiante->dni,
                'puntaje' => $i->puntaje_obtenido,
                'intento' => $i->numero_intento,
                'fecha' => $i->fin?->format('d/m/Y H:i'),
            ]),
        ]);
    }
}
