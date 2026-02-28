<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->esAdministrador();
    }

    public function rules(): array
    {
        return [
            'nombres' => 'required|string|max:50',
            'apellidos' => 'required|string|max:50',
            'dni' => 'required|string|size:8|unique:users,dni',
            'fecha_nacimiento' => 'nullable|date|before:today',
            'sexo' => 'nullable|in:M,F',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
            ],
            'telefono' => 'nullable|string|max:15',
            'direccion' => 'nullable|string|max:200',
            'foto_perfil' => 'nullable|image|max:2048',
            'especialidad' => 'nullable|string|max:100',
            'grado_academico' => 'nullable|string|max:100',
            'cargo' => 'nullable|string|max:50',
            'rol' => 'required|in:administrador,docente,estudiante',
        ];
    }

    public function messages(): array
    {
        return [
            'nombres.required' => 'Los nombres son obligatorios.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'dni.required' => 'El DNI es obligatorio.',
            'dni.size' => 'El DNI debe tener 8 caracteres.',
            'dni.unique' => 'Este DNI ya está registrado.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingrese un correo electrónico válido.',
            'email.unique' => 'Este correo ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.regex' => 'La contraseña debe contener mayúsculas, minúsculas y números.',
            'rol.required' => 'El rol es obligatorio.',
            'rol.in' => 'El rol seleccionado no es válido.',
        ];
    }
}
