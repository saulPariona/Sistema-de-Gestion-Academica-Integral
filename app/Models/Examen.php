<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Examen extends Model
{
    use SoftDeletes;

    protected $table = 'examenes';

    protected $fillable = [
        'curso_id',
        'docente_id',
        'titulo',
        'descripcion',
        'puntaje_total',
        'tiempo_limite',
        'fecha_inicio',
        'fecha_fin',
        'intentos_permitidos',
        'orden_aleatorio_preguntas',
        'orden_aleatorio_alternativas',
        'mostrar_resultados',
        'permitir_revision',
        'navegacion_libre',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'datetime',
            'fecha_fin' => 'datetime',
            'orden_aleatorio_preguntas' => 'boolean',
            'orden_aleatorio_alternativas' => 'boolean',
            'mostrar_resultados' => 'boolean',
            'permitir_revision' => 'boolean',
            'navegacion_libre' => 'boolean',
            'puntaje_total' => 'decimal:2',
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

    public function preguntas(): BelongsToMany
    {
        return $this->belongsToMany(Pregunta::class, 'examen_pregunta')
            ->withPivot('orden');
    }

    public function intentos(): HasMany
    {
        return $this->hasMany(Intento::class, 'examen_id');
    }

    public function estaActivo(): bool
    {
        return $this->estado === 'publicado'
            && now()->between($this->fecha_inicio, $this->fecha_fin);
    }

    public function estaCerrado(): bool
    {
        return $this->estado === 'cerrado' || now()->isAfter($this->fecha_fin);
    }
}
