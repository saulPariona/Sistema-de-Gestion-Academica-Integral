<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMatriculaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->esAdministrador();
    }

    public function rules(): array
    {
        return [
            'estudiante_id' => 'required|exists:users,id',
            'curso_id' => 'required|exists:cursos,id',
            'periodo_id' => 'required|exists:periodos,id',
        ];
    }

    public function messages(): array
    {
        return [
            'estudiante_id.required' => 'El estudiante es obligatorio.',
            'estudiante_id.exists' => 'El estudiante seleccionado no existe.',
            'curso_id.required' => 'El curso es obligatorio.',
            'curso_id.exists' => 'El curso seleccionado no existe.',
            'periodo_id.required' => 'El periodo es obligatorio.',
            'periodo_id.exists' => 'El periodo seleccionado no existe.',
        ];
    }
}
