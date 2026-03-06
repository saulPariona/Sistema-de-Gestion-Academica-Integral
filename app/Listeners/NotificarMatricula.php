<?php

namespace App\Listeners;

use App\Events\EstudianteMatriculado;
use App\Notifications\MatriculaConfirmada;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class NotificarMatricula implements ShouldQueue
{
    public function handle(EstudianteMatriculado $event): void
    {
        $matricula = $event->matricula;

        try {
            $matricula->estudiante->notify(new MatriculaConfirmada($matricula));
            Log::info("Matrícula registrada: {$matricula->estudiante->nombreCompleto()} en {$matricula->curso->nombre}");
        } catch (\Throwable $e) {
            Log::warning("No se pudo notificar matrícula a {$matricula->estudiante->email}: {$e->getMessage()}");
        }
    }
}
