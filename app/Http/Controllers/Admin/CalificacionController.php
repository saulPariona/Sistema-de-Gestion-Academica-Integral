<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\Curso;
use App\Models\Examen;
use App\Models\Periodo;
use Illuminate\Http\Request;

class CalificacionController extends Controller
{
    public function index(Request $request)
    {
        $query = Examen::with(['curso', 'docente', 'intentos.estudiante']);
        $periodos = Periodo::all();

        if ($request->filled('curso_id')) {
            $query->where('curso_id', $request->curso_id);
        }

        if ($request->filled('periodo_id')) {
            $query->where('periodo_id', $request->periodo_id);
        }

        $examenes = $query->paginate(15);
        $cursos = Curso::all();

        return view('admin.calificaciones.index', compact('examenes', 'cursos', 'periodos'));
    }
}
