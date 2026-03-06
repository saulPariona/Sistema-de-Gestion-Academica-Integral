<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePeriodoRequest;
use App\Models\Periodo;
use App\Services\AuditoriaService;

class PeriodoController extends Controller
{
    public function index()
    {
        $periodos = Periodo::orderBy('fecha_inicio', 'desc')->paginate(15);
        return view('admin.periodos.index', compact('periodos'));
    }

    public function create()
    {
        return view('admin.periodos.create');
    }

    public function store(StorePeriodoRequest $request)
    {
        $periodo = Periodo::create($request->validated());
        AuditoriaService::registrar('crear_periodo', 'Periodo', $periodo->id);
        return redirect()->route('admin.periodos')->with('status', 'Periodo creado correctamente.');
    }

    public function edit(int $periodo)
    {
        $periodo = Periodo::findOrFail($periodo);
        return view('admin.periodos.edit', compact('periodo'));
    }

    public function update(StorePeriodoRequest $request, int $periodo)
    {
        $periodo = Periodo::findOrFail($periodo);
        $periodo->update($request->validated());
        AuditoriaService::registrar('actualizar_periodo', 'Periodo', $periodo->id);
        return redirect()->route('admin.periodos')->with('status', 'Periodo actualizado correctamente.');
    }
}
