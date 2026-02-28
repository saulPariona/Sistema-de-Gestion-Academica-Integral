<?php

namespace App\Policies;

use App\Models\Examen;
use App\Models\User;

class ExamenPolicy
{
    public function view(User $user, Examen $examen): bool
    {
        if ($user->esAdministrador()) {
            return true;
        }

        if ($user->esDocente()) {
            return $examen->docente_id === $user->id;
        }

        if ($user->esEstudiante()) {
            return $examen->curso->estudiantes()->whereKey($user->id)->exists()
                && $examen->estado === 'publicado';
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->esDocente();
    }

    public function update(User $user, Examen $examen): bool
    {
        return $user->esDocente() && $examen->docente_id === $user->id && $examen->estado !== 'cerrado';
    }

    public function delete(User $user, Examen $examen): bool
    {
        return $user->esDocente() && $examen->docente_id === $user->id && $examen->estado === 'creado';
    }

    public function rendir(User $user, Examen $examen): bool
    {
        if (!$user->esEstudiante()) {
            return false;
        }

        if (!$examen->estaActivo()) {
            return false;
        }

        $matriculado = $examen->curso->estudiantes()->whereKey($user->id)->exists();
        if (!$matriculado) {
            return false;
        }

        $intentosUsados = $examen->intentos()->where('estudiante_id', $user->id)->count();
        return $intentosUsados < $examen->intentos_permitidos;
    }

    public function publicar(User $user, Examen $examen): bool
    {
        return $user->esDocente() && $examen->docente_id === $user->id && $examen->estado === 'creado';
    }

    public function cerrar(User $user, Examen $examen): bool
    {
        return $user->esDocente() && $examen->docente_id === $user->id && $examen->estado === 'publicado';
    }

    public function verResultados(User $user, Examen $examen): bool
    {
        if ($user->esAdministrador()) {
            return true;
        }

        return $user->esDocente() && $examen->docente_id === $user->id;
    }
}
