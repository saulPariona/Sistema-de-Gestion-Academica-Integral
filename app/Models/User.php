<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    const ROL_ADMINISTRADOR = 'administrador';
    const ROL_DOCENTE = 'docente';
    const ROL_ESTUDIANTE = 'estudiante';

    protected $fillable = [
        'nombres',
        'apellidos',
        'dni',
        'fecha_nacimiento',
        'sexo',
        'email',
        'password',
        'telefono',
        'direccion',
        'foto_perfil',
        'especialidad',
        'grado_academico',
        'cargo',
        'rol',
        'estado',
        'intentos_fallidos',
        'bloqueado_hasta',
        'ultimo_acceso',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'fecha_nacimiento' => 'date',
            'bloqueado_hasta' => 'datetime',
            'ultimo_acceso' => 'datetime',
        ];
    }

    public function nombreCompleto(): string
    {
        return "{$this->nombres} {$this->apellidos}";
    }

    public function esAdministrador(): bool
    {
        return $this->rol === self::ROL_ADMINISTRADOR;
    }

    public function esDocente(): bool
    {
        return $this->rol === self::ROL_DOCENTE;
    }

    public function esEstudiante(): bool
    {
        return $this->rol === self::ROL_ESTUDIANTE;
    }

    public function estaBloqueado(): bool
    {
        return $this->estado === 'bloqueado' ||
            ($this->bloqueado_hasta && $this->bloqueado_hasta->isFuture());
    }

    public function apoderados(): HasMany
    {
        return $this->hasMany(Apoderado::class, 'estudiante_id');
    }

    public function cursosDocente(): BelongsToMany
    {
        return $this->belongsToMany(Curso::class, 'curso_docente', 'docente_id', 'curso_id')->withTimestamps();
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class, 'estudiante_id');
    }

    public function examenes(): HasMany
    {
        return $this->hasMany(Examen::class, 'docente_id');
    }

    public function intentos(): HasMany
    {
        return $this->hasMany(Intento::class, 'estudiante_id');
    }

    public function preguntas(): HasMany
    {
        return $this->hasMany(Pregunta::class, 'docente_id');
    }

    public function historialRoles(): HasMany
    {
        return $this->hasMany(HistorialRol::class, 'user_id');
    }

    public function observacionesRecibidas(): HasMany
        {
        return $this->hasMany(Observacion::class, 'estudiante_id');
    }

    public function observacionesRealizadas(): HasMany
    {
        return $this->hasMany(Observacion::class, 'docente_id');
    }

    public function auditorias(): HasMany
    {
        return $this->hasMany(Auditoria::class, 'user_id');
    }
}
