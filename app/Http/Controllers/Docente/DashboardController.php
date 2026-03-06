<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
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
}
