<?php

namespace App\Http\Requests\Estudiante;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePerfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'telefono' => 'nullable|string|max:15',
            'direccion' => 'nullable|string|max:200',
            'foto_perfil' => 'nullable|image|max:2048',
            'password' => [
                'nullable',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'confirmed',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.regex' => 'La contraseña debe contener mayúsculas, minúsculas y números.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }
}
