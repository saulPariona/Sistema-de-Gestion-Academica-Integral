<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Apoderado extends Model
{
    use SoftDeletes;

    protected $table = 'apoderados';

    protected $fillable = [
        'estudiante_id',
        'nombre_completo',
        'dni',
        'telefono',
        'email',
        'parentesco',
    ];

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }
}
