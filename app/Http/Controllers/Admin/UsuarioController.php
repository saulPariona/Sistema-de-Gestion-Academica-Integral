<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsuarioRequest;
use App\Http\Requests\Admin\UpdateUsuarioRequest;
use App\Models\HistorialRol;
use App\Models\User;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index(Request $request)
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

    public function create()
    {
        return view('admin.usuarios.create');
    }

    public function store(StoreUsuarioRequest $request)
    {
        $datos = $request->validated();

        if ($request->hasFile('foto_perfil')) {
            $datos['foto_perfil'] = $request->file('foto_perfil')->store('fotos', 'public');
        }

        $user = User::create($datos);

        AuditoriaService::registrar('crear_usuario', 'User', $user->id, null, $datos);

        return redirect()->route('admin.usuarios')->with('status', 'Usuario creado correctamente.');
    }

    public function edit(int $usuario)
    {
        $user = User::findOrFail($usuario);
        return view('admin.usuarios.edit', compact('user'));
    }

    public function update(UpdateUsuarioRequest $request, int $usuario)
    {
        $user = User::findOrFail($usuario);
        $datosAnteriores = $user->only(['nombres', 'apellidos', 'email', 'dni', 'telefono', 'direccion', 'rol', 'estado']);
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
                'cambiado_por' => Auth::id(),
            ]);
        }

        $user->update($datos);

        $datosNuevos = $user->only(['nombres', 'apellidos', 'email', 'dni', 'telefono', 'direccion', 'rol', 'estado']);
        AuditoriaService::registrar('actualizar_usuario', 'User', $user->id, $datosAnteriores, $datosNuevos);

        return redirect()->route('admin.usuarios')->with('status', 'Usuario actualizado correctamente.');
    }

    public function toggleEstado(int $usuario)
    {
        $user = User::findOrFail($usuario);
        $estadoAnterior = $user->estado;
        $nuevoEstado = $estadoAnterior === 'activo' ? 'inactivo' : 'activo';
        $user->update(['estado' => $nuevoEstado, 'intentos_fallidos' => 0, 'bloqueado_hasta' => null]);

        AuditoriaService::registrar('cambiar_estado_usuario', 'User', $user->id, ['estado' => $estadoAnterior], ['estado' => $nuevoEstado]);

        return redirect()->route('admin.usuarios')->with('status', "Usuario {$nuevoEstado} correctamente.");
    }

    public function resetPassword(int $usuario)
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
}
