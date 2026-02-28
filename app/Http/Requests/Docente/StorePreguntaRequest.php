<?php

namespace App\Http\Requests\Docente;

use Illuminate\Foundation\Http\FormRequest;

class StorePreguntaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->esDocente();
    }

    public function rules(): array
    {
        return [
            'texto' => 'nullable|string',
            'imagen' => 'nullable|image|max:4096',
            'dificultad' => 'required|in:facil,medio,dificil',
            'puntaje' => 'required|numeric|min:0.01|max:100',
            'alternativas' => 'required|array|min:4',
            'alternativas.*.texto' => 'required|string',
            'alternativas.*.imagen' => 'nullable|image|max:4096',
            'alternativa_correcta' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'dificultad.required' => 'La dificultad es obligatoria.',
            'puntaje.required' => 'El puntaje es obligatorio.',
            'alternativas.required' => 'Las alternativas son obligatorias.',
            'alternativas.min' => 'Debe agregar al menos 4 alternativas.',
            'alternativas.*.texto.required' => 'Todas las alternativas deben tener texto.',
            'alternativa_correcta.required' => 'Debe marcar una alternativa como correcta.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (!$this->texto && !$this->hasFile('imagen')) {
                $validator->errors()->add('texto', 'Debe ingresar texto o imagen para la pregunta.');
            }
        });
    }
}
