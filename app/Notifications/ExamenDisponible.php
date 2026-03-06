<?php

namespace App\Notifications;

use App\Models\Examen;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExamenDisponible extends Notification
{
    use Queueable;

    public function __construct(
        public Examen $examen
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Nuevo examen disponible: {$this->examen->titulo}")
            ->greeting("Hola {$notifiable->nombres},")
            ->line("Se ha publicado un nuevo examen en el curso **{$this->examen->curso->nombre}**.")
            ->line("**Examen:** {$this->examen->titulo}")
            ->line("**Disponible desde:** {$this->examen->fecha_inicio->format('d/m/Y H:i')}")
            ->line("**Disponible hasta:** {$this->examen->fecha_fin->format('d/m/Y H:i')}")
            ->line("**Tiempo límite:** {$this->examen->tiempo_limite} minutos")
            ->line("**Intentos permitidos:** {$this->examen->intentos_permitidos}")
            ->action('Ir al curso', url('/estudiante/curso/' . $this->examen->curso_id . '/examenes'))
            ->salutation('Colegio Max Planck');
    }
}
