<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMatriculaRequest;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Periodo;
use App\Models\User;
use App\Events\EstudianteMatriculado;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    public function index(Request $request)
    {
        $query = Matricula::with(['estudiante', 'curso', 'periodo']);

        if ($request->filled('curso_id')) {
            $query->where('curso_id', $request->curso_id);
        }

        if ($request->filled('periodo_id')) {
            $query->where('periodo_id', $request->periodo_id);
        }

        $matriculas = $query->paginate(20);
        $cursos = Curso::all();
        $periodos = Periodo::all();

        return view('admin.matriculas.index', compact('matriculas', 'cursos', 'periodos'));
    }

    public function create()
    {
        $estudiantes = User::where('rol', 'estudiante')->where('estado', 'activo')->orderBy('apellidos')->get();
        $cursos = Curso::all();
        $periodos = Periodo::where('estado', 'activo')->get();
        return view('admin.matriculas.create', compact('estudiantes', 'cursos', 'periodos'));
    }

    public function store(StoreMatriculaRequest $request)
    {
        $existe = Matricula::where('estudiante_id', $request->estudiante_id)
            ->where('curso_id', $request->curso_id)
            ->where('periodo_id', $request->periodo_id)
            ->exists();

        if ($existe) {
            return back()->with('error', 'El estudiante ya está matriculado en este curso.')->withInput();
        }

        $matricula = Matricula::create($request->validated());
        AuditoriaService::registrar('crear_matricula', 'Matricula', $matricula->id);
        EstudianteMatriculado::dispatch($matricula);
        return redirect()->route('admin.matriculas')->with('status', 'Matrícula registrada correctamente.');
    }
}
