<?php

namespace App\Http\Requests\Docente;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExamenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->esDocente();
    }

    public function rules(): array
    {
        return [
            'titulo' => 'required|string|max:200',
            'descripcion' => 'nullable|string',
            'puntaje_total' => 'required|numeric|min:1|max:100',
            'tiempo_limite' => 'nullable|integer|min:1|max:300',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'intentos_permitidos' => 'required|integer|min:1|max:5',
            'orden_aleatorio_preguntas' => 'boolean',
            'orden_aleatorio_alternativas' => 'boolean',
            'mostrar_resultados' => 'boolean',
            'permitir_revision' => 'boolean',
            'navegacion_libre' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'El título del examen es obligatorio.',
            'puntaje_total.required' => 'El puntaje total es obligatorio.',
            'tiempo_limite.min' => 'El tiempo límite debe ser al menos 1 minuto.',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_fin.required' => 'La fecha de fin es obligatoria.',
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
        ];
    }
}
