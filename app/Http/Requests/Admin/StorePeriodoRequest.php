<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePeriodoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->esAdministrador();
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:50',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'estado' => 'required|in:activo,inactivo',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del periodo es obligatorio.',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_fin.required' => 'La fecha de fin es obligatoria.',
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
        ];
    }
}
