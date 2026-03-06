<?php

namespace App\Observers;

use App\Models\Examen;
use App\Services\AuditoriaService;

class ExamenObserver
{
    public function created(Examen $examen): void
    {
        AuditoriaService::registrar(
            'crear_examen',
            'Examen',
            $examen->id,
            null,
            $examen->only(['titulo', 'curso_id', 'estado'])
        );
    }

    public function deleted(Examen $examen): void
    {
        AuditoriaService::registrar(
            'eliminar_examen',
            'Examen',
            $examen->id,
            $examen->only(['titulo', 'curso_id', 'estado']),
            null
        );
    }
}
