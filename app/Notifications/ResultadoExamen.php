<?php

namespace App\Notifications;

use App\Models\Intento;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResultadoExamen extends Notification
{
    use Queueable;

    public function __construct(
        public Intento $intento
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $examen = $this->intento->examen;

        return (new MailMessage)
            ->subject("Resultado de tu examen: {$examen->titulo}")
            ->greeting("Hola {$notifiable->nombres},")
            ->line("Has finalizado el examen **{$examen->titulo}** del curso **{$examen->curso->nombre}**.")
            ->line("**Intento N°:** {$this->intento->numero_intento}")
            ->line("**Puntaje obtenido:** {$this->intento->puntaje_obtenido} / {$examen->puntaje_total}")
            ->action('Ver resultado', url("/estudiante/curso/{$examen->curso_id}/examenes/{$examen->id}/resultado/{$this->intento->id}"))
            ->salutation('Colegio Max Planck');
    }
}
