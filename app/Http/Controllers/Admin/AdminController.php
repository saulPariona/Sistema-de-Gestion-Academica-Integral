<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsuarioRequest;
use App\Http\Requests\Admin\UpdateUsuarioRequest;
use App\Http\Requests\Admin\StorePeriodoRequest;
use App\Http\Requests\Admin\StoreCursoRequest;
use App\Http\Requests\Admin\StoreMatriculaRequest;
use App\Http\Requests\Admin\StoreApoderadoRequest;
use App\Models\Apoderado;
use App\Models\Auditoria;
use App\Models\Curso;
use App\Models\Examen;
use App\Models\HistorialRol;
use App\Models\Matricula;
use App\Models\Periodo;
use App\Models\User;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    public function dashboard()
    {
        $totalEstudiantes = User::where('rol', 'estudiante')->count();
        $totalDocentes = User::where('rol', 'docente')->count();
        $totalCursos = Curso::count();
        $periodoActivo = Periodo::where('estado', 'activo')->first();

        return view('admin.dashboard', compact('totalEstudiantes', 'totalDocentes', 'totalCursos', 'periodoActivo'));
    }

    public function usuarios(Request $request)
    {
        $query = User::query();

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('nombres', 'like', "%{$buscar}%")
                    ->orWhere('apellidos', 'like', "%{$buscar}%")
                    ->orWhere('dni', 'like', "%{$buscar}%")
                    ->orWhere('email', 'like', "%{$buscar}%");
            });
        }

        if ($request->filled('rol')) {
            $query->where('rol', $request->rol);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $usuarios = $query->orderBy('apellidos')->paginate(20);

        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function crearUsuario()
    {
        return view('admin.usuarios.create');
    }

    public function guardarUsuario(StoreUsuarioRequest $request)
    {
        $datos = $request->validated();

        if ($request->hasFile('foto_perfil')) {
            $datos['foto_perfil'] = $request->file('foto_perfil')->store('fotos', 'public');
        }

        $user = User::create($datos);

        AuditoriaService::registrar('crear_usuario', 'User', $user->id, null, $datos);

        return redirect()->route('admin.usuarios')->with('status', 'Usuario creado correctamente.');
    }

    public function editarUsuario(int $usuario)
    {
        $user = User::findOrFail($usuario);
        return view('admin.usuarios.edit', compact('user'));
    }

    public function actualizarUsuario(UpdateUsuarioRequest $request, int $usuario)
    {
        $user = User::findOrFail($usuario);
        $datosAnteriores = $user->toArray();
        $datos = $request->validated();

        if ($request->hasFile('foto_perfil')) {
            $datos['foto_perfil'] = $request->file('foto_perfil')->store('fotos', 'public');
        }

        if (empty($datos['password'])) {
            unset($datos['password']);
        }

        if (isset($datos['rol']) && $datos['rol'] !== $user->rol) {
            HistorialRol::create([
                'user_id' => $user->id,
                'rol_anterior' => $user->rol,
                'rol_nuevo' => $datos['rol'],
                'cambiado_por' => auth()->id(),
            ]);
        }

        $user->update($datos);

        AuditoriaService::registrar('actualizar_usuario', 'User', $user->id, $datosAnteriores, $datos);

        return redirect()->route('admin.usuarios')->with('status', 'Usuario actualizado correctamente.');
    }

    public function toggleEstadoUsuario(int $usuario)
    {
        $user = User::findOrFail($usuario);
        $nuevoEstado = $user->estado === 'activo' ? 'inactivo' : 'activo';
        $user->update(['estado' => $nuevoEstado, 'intentos_fallidos' => 0, 'bloqueado_hasta' => null]);

        AuditoriaService::registrar('cambiar_estado_usuario', 'User', $user->id, ['estado' => $user->estado], ['estado' => $nuevoEstado]);

        return redirect()->route('admin.usuarios')->with('status', "Usuario {$nuevoEstado} correctamente.");
    }

    public function resetPasswordUsuario(int $usuario)
    {
        $user = User::findOrFail($usuario);
        $user->update([
            'password' => Hash::make('Temporal1'),
            'intentos_fallidos' => 0,
            'bloqueado_hasta' => null,
            'estado' => 'activo',
        ]);

        AuditoriaService::registrar('reset_password_admin', 'User', $user->id);

        return redirect()->route('admin.usuarios')->with('status', 'Contraseña reseteada. Nueva contraseña: Temporal1');
    }

    public function periodos()
    {
        $periodos = Periodo::orderBy('fecha_inicio', 'desc')->paginate(15);
        return view('admin.periodos.index', compact('periodos'));
    }

    public function crearPeriodo()
    {
        return view('admin.periodos.create');
    }

    public function guardarPeriodo(StorePeriodoRequest $request)
    {
        $periodo = Periodo::create($request->validated());
        AuditoriaService::registrar('crear_periodo', 'Periodo', $periodo->id);
        return redirect()->route('admin.periodos')->with('status', 'Periodo creado correctamente.');
    }

    public function editarPeriodo(int $periodo)
    {
        $periodo = Periodo::findOrFail($periodo);
        return view('admin.periodos.edit', compact('periodo'));
    }

    public function actualizarPeriodo(StorePeriodoRequest $request, int $periodo)
    {
        $periodo = Periodo::findOrFail($periodo);
        $periodo->update($request->validated());
        AuditoriaService::registrar('actualizar_periodo', 'Periodo', $periodo->id);
        return redirect()->route('admin.periodos')->with('status', 'Periodo actualizado correctamente.');
    }

    public function cursos(Request $request)
    {
        $query = Curso::with(['periodo', 'docentes']);

        if ($request->filled('periodo_id')) {
            $query->where('periodo_id', $request->periodo_id);
        }

        $cursos = $query->orderBy('nombre')->paginate(15);
        $periodos = Periodo::all();

        return view('admin.cursos.index', compact('cursos', 'periodos'));
    }

    public function crearCurso()
    {
        $periodos = Periodo::where('estado', 'activo')->get();
        $docentes = User::where('rol', 'docente')->where('estado', 'activo')->orderBy('apellidos')->get();
        return view('admin.cursos.create', compact('periodos', 'docentes'));
    }

    public function guardarCurso(StoreCursoRequest $request)
    {
        $curso = Curso::create($request->validated());

        if ($request->filled('docente_id')) {
            $curso->docentes()->attach($request->docente_id);
        }

        AuditoriaService::registrar('crear_curso', 'Curso', $curso->id);
        return redirect()->route('admin.cursos')->with('status', 'Curso creado correctamente.');
    }

    public function editarCurso(int $curso)
    {
        $curso = Curso::with('docentes')->findOrFail($curso);
        $periodos = Periodo::all();
        $docentes = User::where('rol', 'docente')->where('estado', 'activo')->orderBy('apellidos')->get();
        return view('admin.cursos.edit', compact('curso', 'periodos', 'docentes'));
    }

    public function actualizarCurso(StoreCursoRequest $request, int $curso)
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

    public function matriculas(Request $request)
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

    public function crearMatricula()
    {
        $estudiantes = User::where('rol', 'estudiante')->where('estado', 'activo')->orderBy('apellidos')->get();
        $cursos = Curso::all();
        $periodos = Periodo::where('estado', 'activo')->get();
        return view('admin.matriculas.create', compact('estudiantes', 'cursos', 'periodos'));
    }

    public function guardarMatricula(StoreMatriculaRequest $request)
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
        return redirect()->route('admin.matriculas')->with('status', 'Matrícula registrada correctamente.');
    }

    public function apoderados(int $estudiante)
    {
        $estudiante = User::where('rol', 'estudiante')->findOrFail($estudiante);
        $apoderados = $estudiante->apoderados;
        return view('admin.apoderados.index', compact('estudiante', 'apoderados'));
    }

    public function crearApoderado(int $estudiante)
    {
        $estudiante = User::findOrFail($estudiante);
        return view('admin.apoderados.create', compact('estudiante'));
    }

    public function guardarApoderado(StoreApoderadoRequest $request)
    {
        $apoderado = Apoderado::create($request->validated());
        AuditoriaService::registrar('crear_apoderado', 'Apoderado', $apoderado->id);
        return redirect()->route('admin.apoderados', $request->estudiante_id)->with('status', 'Apoderado registrado correctamente.');
    }

    public function calificaciones(Request $request)
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

    public function auditorias(Request $request)
    {
        $query = Auditoria::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }

        $auditorias = $query->paginate(30);

        return view('admin.auditorias.index', compact('auditorias'));
    }
}
