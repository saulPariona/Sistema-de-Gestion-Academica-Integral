<?php

namespace App\Services;

use App\Models\Auditoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuditoriaService
{
    public static function registrar(string $accion, string $modelo, ?int $modeloId = null, ?array $datosAnteriores = null, ?array $datosNuevos = null): void
    {
        Auditoria::create([
            'user_id' => Auth::id(),
            'accion' => $accion,
            'modelo' => $modelo,
            'modelo_id' => $modeloId,
            'datos_anteriores' => $datosAnteriores,
            'datos_nuevos' => $datosNuevos,
            'ip' => request()->ip(),
        ]);
    }
}
