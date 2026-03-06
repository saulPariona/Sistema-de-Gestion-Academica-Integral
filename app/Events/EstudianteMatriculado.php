<?php

namespace App\Events;

use App\Models\Matricula;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EstudianteMatriculado
{
    use Dispatchable, SerializesModels;

    public function __construct(public Matricula $matricula)
    {
    }
}
