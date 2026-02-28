<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Intento extends Model
{
    protected $table = 'intentos';

    protected $fillable = [
        'examen_id',
        'estudiante_id',
        'numero_intento',
        'inicio',
        'fin',
        'puntaje_obtenido',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'inicio' => 'datetime',
            'fin' => 'datetime',
            'puntaje_obtenido' => 'decimal:2',
        ];
    }

    public function examen(): BelongsTo
    {
        return $this->belongsTo(Examen::class, 'examen_id');
    }

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }

    public function respuestas(): HasMany
    {
        return $this->hasMany(Respuesta::class, 'intento_id');
    }

    public function estaEnProgreso(): bool
    {
        return $this->estado === 'en_progreso';
    }

    public function tiempoRestante(): int
    {
        $limiteMinutos = $this->examen->tiempo_limite;
        $transcurrido = $this->inicio->diffInSeconds(now());
        $limiteSegundos = $limiteMinutos * 60;
        return max(0, $limiteSegundos - $transcurrido);
    }

    public function calcularPuntaje(): float
    {
        $totalPreguntas = $this->examen->preguntas->count();
        if ($totalPreguntas === 0) return 0;

        $correctas = 0;
        foreach ($this->respuestas as $respuesta) {
            if ($respuesta->alternativa && $respuesta->alternativa->es_correcta) {
                $correctas++;
            }
        }

        return round(($correctas / $totalPreguntas) * $this->examen->puntaje_total, 2);
    }
}
