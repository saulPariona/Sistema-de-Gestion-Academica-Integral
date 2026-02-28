<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Periodo extends Model
{
    protected $table = 'periodos';

    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
        ];
    }

    public function cursos(): HasMany
    {
        return $this->hasMany(Curso::class, 'periodo_id');
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class, 'periodo_id');
    }

    public function estaActivo(): bool
    {
        return $this->estado === 'activo';
    }
}
