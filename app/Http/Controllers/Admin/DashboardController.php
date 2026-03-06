<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Examen;
use App\Models\Periodo;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $totalEstudiantes = User::where('rol', 'estudiante')->count();
        $totalDocentes = User::where('rol', 'docente')->count();
        $totalCursos = Curso::count();
        $periodoActivo = Periodo::where('estado', 'activo')->first();

        return view('admin.dashboard', compact('totalEstudiantes', 'totalDocentes', 'totalCursos', 'periodoActivo'));
    }
}
