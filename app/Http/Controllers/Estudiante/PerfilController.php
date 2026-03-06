<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use App\Http\Requests\Estudiante\UpdatePerfilRequest;
use App\Services\AuditoriaService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('estudiante.perfil', compact('user'));
    }

    public function update(UpdatePerfilRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $datosAnteriores = $user->only(['telefono', 'direccion']);
        $datos = $request->only(['telefono', 'direccion']);

        if ($request->hasFile('foto_perfil')) {
            $datos['foto_perfil'] = $request->file('foto_perfil')->store('fotos', 'public');
        }

        if ($request->filled('password')) {
            $datos['password'] = Hash::make($request->password);
        }

        $user->update($datos);

        $datosNuevos = $user->only(['telefono', 'direccion']);
        AuditoriaService::registrar('actualizar_perfil', 'User', $user->id, $datosAnteriores, $datosNuevos);
        return back()->with('status', 'Perfil actualizado correctamente.');
    }
}
