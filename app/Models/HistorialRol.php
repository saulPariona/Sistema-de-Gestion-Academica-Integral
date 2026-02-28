<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistorialRol extends Model
{
    protected $table = 'historial_roles';

    protected $fillable = [
        'user_id',
        'rol_anterior',
        'rol_nuevo',
        'cambiado_por',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function administrador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cambiado_por');
    }
}
