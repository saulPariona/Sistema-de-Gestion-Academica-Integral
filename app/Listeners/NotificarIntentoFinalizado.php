<?php

namespace App\Listeners;

use App\Events\IntentoFinalizado;
use App\Notifications\ResultadoExamen;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class NotificarIntentoFinalizado implements ShouldQueue
{
    public function handle(IntentoFinalizado $event): void
    {
        $intento = $event->intento;

        try {
            $intento->estudiante->notify(new ResultadoExamen($intento));
            Log::info("Intento finalizado: Examen '{$intento->examen->titulo}' por {$intento->estudiante->nombreCompleto()} - Puntaje: {$intento->puntaje_obtenido}");
        } catch (\Throwable $e) {
            Log::warning("No se pudo notificar resultado a {$intento->estudiante->email}: {$e->getMessage()}");
        }
    }
}
