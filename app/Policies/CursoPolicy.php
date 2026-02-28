<?php

namespace App\Policies;

use App\Models\Curso;
use App\Models\User;

class CursoPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Curso $curso): bool
    {
        if ($user->esAdministrador()) {
            return true;
        }

        if ($user->esDocente()) {
            return $curso->docentes()->whereKey($user->id)->exists();
        }

        if ($user->esEstudiante()) {
            return $curso->estudiantes()->whereKey($user->id)->exists();
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->esAdministrador();
    }

    public function update(User $user, Curso $curso): bool
    {
        return $user->esAdministrador();
    }

    public function delete(User $user, Curso $curso): bool
    {
        return $user->esAdministrador();
    }

    public function gestionar(User $user, Curso $curso): bool
    {
        if ($user->esAdministrador()) {
            return true;
        }

        return $user->esDocente() && $curso->docentes()->whereKey($user->id)->exists();
    }
}
