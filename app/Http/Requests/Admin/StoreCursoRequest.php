<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCursoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->esAdministrador();
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'periodo_id' => 'required|exists:periodos,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del curso es obligatorio.',
            'periodo_id.required' => 'El periodo académico es obligatorio.',
            'periodo_id.exists' => 'El periodo seleccionado no existe.',
        ];
    }
}
