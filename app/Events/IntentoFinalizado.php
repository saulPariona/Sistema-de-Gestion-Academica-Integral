<?php

namespace App\Events;

use App\Models\Intento;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IntentoFinalizado
{
    use Dispatchable, SerializesModels;

    public function __construct(public Intento $intento)
    {
    }
}
