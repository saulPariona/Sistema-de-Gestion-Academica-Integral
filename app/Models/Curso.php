<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Curso extends Model
{
    use SoftDeletes;

    protected $table = 'cursos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'periodo_id',
    ];

    public function periodo(): BelongsTo
    {
        return $this->belongsTo(Periodo::class, 'periodo_id');
    }

    public function docentes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'curso_docente', 'curso_id', 'docente_id')->withTimestamps();
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class, 'curso_id');
    }

    public function estudiantes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'matriculas', 'curso_id', 'estudiante_id')
            ->wherePivot('estado', 'activa');
    }

    public function examenes(): HasMany
    {
        return $this->hasMany(Examen::class, 'curso_id');
    }

    public function preguntas(): HasMany
    {
        return $this->hasMany(Pregunta::class, 'curso_id');
    }

    public function observaciones(): HasMany
    {
        return $this->hasMany(Observacion::class, 'curso_id');
    }
}
