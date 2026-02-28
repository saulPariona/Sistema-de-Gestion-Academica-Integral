@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Resultados - {{ $examen->titulo }}</h1>
        <a href="{{ route('docente.examenes', $curso) }}" class="text-blue-600 hover:underline text-sm">← Volver</a>
    </div>

    <div class="bg-white rounded border border-gray-200 mb-4 p-4">
        <div class="grid grid-cols-3 gap-4 text-sm">
            <div><span class="font-semibold">Curso:</span> {{ $curso->nombre }}</div>
            <div><span class="font-semibold">Puntaje Total:</span> {{ $examen->puntaje_total }}</div>
            <div><span class="font-semibold">Intentos:</span> {{ $examen->intentos->count() }}</div>
        </div>
    </div>

    <div class="bg-white rounded border border-gray-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="p-3 text-left">Estudiante</th>
                    <th class="p-3 text-left">Intento #</th>
                    <th class="p-3 text-left">Inicio</th>
                    <th class="p-3 text-left">Fin</th>
                    <th class="p-3 text-left">Puntaje</th>
                    <th class="p-3 text-left">Nota (base 20)</th>
                    <th class="p-3 text-left">Estado</th>
                    <th class="p-3 text-left">Detalle</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($examen->intentos as $intento)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $intento->estudiante->nombreCompleto() }}</td>
                        <td class="p-3">{{ $intento->numero_intento }}</td>
                        <td class="p-3">{{ $intento->inicio->format('d/m/Y H:i') }}</td>
                        <td class="p-3">{{ $intento->fin ? $intento->fin->format('d/m/Y H:i') : '-' }}</td>
                        <td class="p-3 font-semibold">{{ $intento->puntaje_obtenido ?? '-' }} /
                            {{ $examen->puntaje_total }}</td>
                        <td class="p-3">
                            @if ($intento->puntaje_obtenido !== null && $examen->puntaje_total > 0)
                                <span
                                    class="font-semibold {{ ($intento->puntaje_obtenido / $examen->puntaje_total) * 20 >= 11 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format(($intento->puntaje_obtenido / $examen->puntaje_total) * 20, 1) }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="p-3">
                            <span
                                class="px-2 py-1 rounded text-xs {{ $intento->estado == 'finalizado' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($intento->estado) }}
                            </span>
                        </td>
                        <td class="p-3">
                            <a href="{{ route('docente.examenes.resultado-estudiante', [$curso, $examen, $intento]) }}"
                                class="text-blue-600 hover:underline text-xs">Ver Detalle</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-3 text-center text-gray-500">No hay intentos registrados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
