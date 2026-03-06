<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreApoderadoRequest;
use App\Models\Apoderado;
use App\Models\User;
use App\Services\AuditoriaService;

class ApoderadoController extends Controller
{
    public function index(int $estudiante)
    {
        $estudiante = User::where('rol', 'estudiante')->findOrFail($estudiante);
        $apoderados = $estudiante->apoderados;
        return view('admin.apoderados.index', compact('estudiante', 'apoderados'));
    }

    public function create(int $estudiante)
    {
        $estudiante = User::findOrFail($estudiante);
        return view('admin.apoderados.create', compact('estudiante'));
    }

    public function store(StoreApoderadoRequest $request)
    {
        $apoderado = Apoderado::create($request->validated());
        AuditoriaService::registrar('crear_apoderado', 'Apoderado', $apoderado->id);
        return redirect()->route('admin.apoderados', $request->estudiante_id)->with('status', 'Apoderado registrado correctamente.');
    }
}
