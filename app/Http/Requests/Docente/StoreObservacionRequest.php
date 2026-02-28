<?php

namespace App\Http\Requests\Docente;

use Illuminate\Foundation\Http\FormRequest;

class StoreObservacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->esDocente();
    }

    public function rules(): array
    {
        return [
            'estudiante_id' => 'required|exists:users,id',
            'curso_id' => 'required|exists:cursos,id',
            'texto' => 'required|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'estudiante_id.required' => 'El estudiante es obligatorio.',
            'curso_id.required' => 'El curso es obligatorio.',
            'texto.required' => 'La observación es obligatoria.',
        ];
    }
}
