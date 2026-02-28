<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreApoderadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->esAdministrador();
    }

    public function rules(): array
    {
        return [
            'estudiante_id' => 'required|exists:users,id',
            'nombre_completo' => 'required|string|max:100',
            'dni' => 'required|string|size:8',
            'telefono' => 'required|string|max:15',
            'email' => 'nullable|email',
            'parentesco' => 'required|string|max:30',
        ];
    }

    public function messages(): array
    {
        return [
            'estudiante_id.required' => 'El estudiante es obligatorio.',
            'nombre_completo.required' => 'El nombre del apoderado es obligatorio.',
            'dni.required' => 'El DNI del apoderado es obligatorio.',
            'dni.size' => 'El DNI debe tener 8 caracteres.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'parentesco.required' => 'El parentesco es obligatorio.',
        ];
    }
}
