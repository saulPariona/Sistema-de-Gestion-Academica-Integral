<?php

namespace App\Notifications;

use App\Models\Matricula;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MatriculaConfirmada extends Notification
{
    use Queueable;

    public function __construct(
        public Matricula $matricula
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Matrícula confirmada: {$this->matricula->curso->nombre}")
            ->greeting("Hola {$notifiable->nombres},")
            ->line("Tu matrícula ha sido registrada exitosamente.")
            ->line("**Curso:** {$this->matricula->curso->nombre}")
            ->line("**Periodo:** {$this->matricula->periodo->nombre}")
            ->line("**Estado:** Activa")
            ->action('Ir al curso', url('/estudiante/curso/' . $this->matricula->curso_id . '/examenes'))
            ->salutation('Colegio Max Planck');
    }
}
