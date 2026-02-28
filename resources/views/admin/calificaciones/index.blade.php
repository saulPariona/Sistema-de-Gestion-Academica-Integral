@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Calificaciones</h1>
    </div>

    <form method="get" class="flex gap-3 mb-4 bg-white p-3 rounded border border-gray-200">
        <select name="curso_id" class="p-2 text-sm border border-gray-300 rounded">
            <option value="">Todos los cursos</option>
            @foreach ($cursos as $curso)
                <option value="{{ $curso->id }}" {{ request('curso_id') == $curso->id ? 'selected' : '' }}>
                    {{ $curso->nombre }}</option>
            @endforeach
        </select>
        <select name="periodo_id" class="p-2 text-sm border border-gray-300 rounded">
            <option value="">Todos los periodos</option>
            @foreach ($periodos as $periodo)
                <option value="{{ $periodo->id }}" {{ request('periodo_id') == $periodo->id ? 'selected' : '' }}>
                    {{ $periodo->nombre }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded text-sm">Filtrar</button>
    </form>

    @foreach ($examenes as $examen)
        <div class="bg-white rounded border border-gray-200 mb-4">
            <div class="p-3 bg-gray-50 border-b flex justify-between items-center">
                <div>
                    <span class="font-semibold text-gray-800">{{ $examen->titulo }}</span>
                    <span class="text-gray-500 text-sm ml-2">{{ $examen->curso->nombre }}</span>
                </div>
                <span class="text-xs text-gray-500">Puntaje total: {{ $examen->puntaje_total }}</span>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="p-3 text-left">Estudiante</th>
                        <th class="p-3 text-left">Intento</th>
                        <th class="p-3 text-left">Puntaje</th>
                        <th class="p-3 text-left">Nota (base 20)</th>
                        <th class="p-3 text-left">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($examen->intentos as $intento)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">{{ $intento->estudiante->nombreCompleto() }}</td>
                            <td class="p-3">{{ $intento->numero_intento }}</td>
                            <td class="p-3">{{ $intento->puntaje_obtenido ?? '-' }} / {{ $examen->puntaje_total }}</td>
                            <td class="p-3">
                                @if ($intento->puntaje_obtenido !== null && $examen->puntaje_total > 0)
                                    {{ number_format(($intento->puntaje_obtenido / $examen->puntaje_total) * 20, 1) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="p-3">
                                <span
                                    class="px-2 py-1 rounded text-xs {{ $intento->estado == 'finalizado' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $intento->estado }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    <div class="mt-4">{{ $examenes->withQueryString()->links() }}</div>
@endsection
