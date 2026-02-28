<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Respuesta extends Model
{
    protected $table = 'respuestas';

    protected $fillable = [
        'intento_id',
        'pregunta_id',
        'alternativa_id',
    ];

    public function intento(): BelongsTo
    {
        return $this->belongsTo(Intento::class, 'intento_id');
    }

    public function pregunta(): BelongsTo
    {
        return $this->belongsTo(Pregunta::class, 'pregunta_id');
    }

    public function alternativa(): BelongsTo
    {
        return $this->belongsTo(Alternativa::class, 'alternativa_id');
    }

    public function esCorrecta(): bool
    {
        return $this->alternativa && $this->alternativa->es_correcta;
    }
}
