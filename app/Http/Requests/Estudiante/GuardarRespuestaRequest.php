<?php

namespace App\Http\Requests\Estudiante;

use Illuminate\Foundation\Http\FormRequest;

class GuardarRespuestaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->esEstudiante();
    }

    public function rules(): array
    {
        return [
            'pregunta_id' => 'required|exists:preguntas,id',
            'alternativa_id' => 'required|exists:alternativas,id',
        ];
    }

    public function messages(): array
    {
        return [
            'pregunta_id.required' => 'La pregunta es obligatoria.',
            'alternativa_id.required' => 'Debe seleccionar una alternativa.',
        ];
    }
}
