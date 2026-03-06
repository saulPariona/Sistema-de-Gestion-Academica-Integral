<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCursoRequest;
use App\Models\Curso;
use App\Models\Periodo;
use App\Models\User;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function index(Request $request)
    {
        $query = Curso::with(['periodo', 'docentes']);

        if ($request->filled('periodo_id')) {
            $query->where('periodo_id', $request->periodo_id);
        }

        $cursos = $query->orderBy('nombre')->paginate(15);
        $periodos = Periodo::all();

        return view('admin.cursos.index', compact('cursos', 'periodos'));
    }

    public function create()
    {
        $periodos = Periodo::where('estado', 'activo')->get();
        $docentes = User::where('rol', 'docente')->where('estado', 'activo')->orderBy('apellidos')->get();
        return view('admin.cursos.create', compact('periodos', 'docentes'));
    }

    public function store(StoreCursoRequest $request)
    {
        $curso = Curso::create($request->validated());

        if ($request->filled('docente_id')) {
            $curso->docentes()->attach($request->docente_id);
        }

        AuditoriaService::registrar('crear_curso', 'Curso', $curso->id);
        return redirect()->route('admin.cursos')->with('status', 'Curso creado correctamente.');
    }

    public function edit(int $curso)
    {
        $curso = Curso::with('docentes')->findOrFail($curso);
        $periodos = Periodo::all();
        $docentes = User::where('rol', 'docente')->where('estado', 'activo')->orderBy('apellidos')->get();
        return view('admin.cursos.edit', compact('curso', 'periodos', 'docentes'));
    }

    public function update(StoreCursoRequest $request, int $curso)
    {
        $curso = Curso::findOrFail($curso);
        $curso->update($request->validated());

        if ($request->filled('docente_id')) {
            $curso->docentes()->sync($request->docente_id);
        }

        AuditoriaService::registrar('actualizar_curso', 'Curso', $curso->id);
        return redirect()->route('admin.cursos')->with('status', 'Curso actualizado correctamente.');
    }

    public function asignarDocente(int $curso)
    {
        $curso = Curso::with('docentes')->findOrFail($curso);
        $docentes = User::where('rol', 'docente')->where('estado', 'activo')->orderBy('apellidos')->get();
        return view('admin.cursos.asignar-docente', compact('curso', 'docentes'));
    }

    public function guardarAsignacionDocente(Request $request, int $curso)
    {
        $request->validate(['docente_id' => 'required|exists:users,id']);
        $curso = Curso::findOrFail($curso);
        $curso->docentes()->syncWithoutDetaching([$request->docente_id]);
        AuditoriaService::registrar('asignar_docente', 'Curso', $curso->id);
        return redirect()->route('admin.cursos')->with('status', 'Docente asignado correctamente.');
    }
}
