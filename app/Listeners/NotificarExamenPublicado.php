<?php

namespace App\Listeners;

use App\Events\ExamenPublicado;
use App\Notifications\ExamenDisponible;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class NotificarExamenPublicado implements ShouldQueue
{
    public function handle(ExamenPublicado $event): void
    {
        $examen = $event->examen;
        $curso = $examen->curso;
        $estudiantes = $curso->estudiantes;
        $notificados = 0;

        foreach ($estudiantes as $estudiante) {
            try {
                $estudiante->notify(new ExamenDisponible($examen));
                $notificados++;
            } catch (\Throwable $e) {
                Log::warning("No se pudo notificar a {$estudiante->email}: {$e->getMessage()}");
            }
        }

        Log::info("Examen publicado: {$examen->titulo} - Notificados {$notificados}/{$estudiantes->count()} estudiantes");
    }
}
