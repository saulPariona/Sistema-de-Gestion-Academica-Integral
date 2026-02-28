<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pregunta extends Model
{
    use SoftDeletes;

    protected $table = 'preguntas';

    protected $fillable = [
        'curso_id',
        'docente_id',
        'texto',
        'imagen',
        'dificultad',
        'puntaje',
    ];

    protected function casts(): array
    {
        return [
            'puntaje' => 'decimal:2',
        ];
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    public function docente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'docente_id');
    }

    public function alternativas(): HasMany
    {
        return $this->hasMany(Alternativa::class, 'pregunta_id');
    }

    public function examenes(): BelongsToMany
    {
        return $this->belongsToMany(Examen::class, 'examen_pregunta')
            ->withPivot('orden');
    }

    public function respuestas(): HasMany
    {
        return $this->hasMany(Respuesta::class, 'pregunta_id');
    }

    public function alternativaCorrecta()
    {
        return $this->alternativas()->where('es_correcta', true)->first();
    }
}
