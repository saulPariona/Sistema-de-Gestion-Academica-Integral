<?php

namespace App\Policies;

use App\Models\Pregunta;
use App\Models\User;

class PreguntaPolicy
{
    public function view(User $user, Pregunta $pregunta): bool
    {
        if ($user->esAdministrador()) {
            return true;
        }

        return $user->esDocente() && $pregunta->docente_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->esDocente();
    }

    public function update(User $user, Pregunta $pregunta): bool
    {
        return $user->esDocente() && $pregunta->docente_id === $user->id;
    }

    public function delete(User $user, Pregunta $pregunta): bool
    {
        return $user->esDocente() && $pregunta->docente_id === $user->id;
    }
}
