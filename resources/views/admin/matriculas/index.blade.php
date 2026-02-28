@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Matrículas</h1>
        <a href="{{ route('admin.matriculas.crear') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm font-semibold">Nueva Matrícula</a>
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

    <div class="bg-white rounded border border-gray-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="p-3 text-left">Estudiante</th>
                    <th class="p-3 text-left">DNI</th>
                    <th class="p-3 text-left">Curso</th>
                    <th class="p-3 text-left">Periodo</th>
                    <th class="p-3 text-left">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($matriculas as $matricula)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $matricula->estudiante->nombreCompleto() }}</td>
                        <td class="p-3">{{ $matricula->estudiante->dni }}</td>
                        <td class="p-3">{{ $matricula->curso->nombre }}</td>
                        <td class="p-3">{{ $matricula->periodo->nombre }}</td>
                        <td class="p-3">
                            <span
                                class="px-2 py-1 rounded text-xs {{ $matricula->estado == 'activa' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $matricula->estado }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $matriculas->withQueryString()->links() }}</div>
@endsection
