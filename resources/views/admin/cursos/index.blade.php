@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Gestión de Cursos</h1>
        <a href="{{ route('admin.cursos.crear') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm font-semibold">Nuevo Curso</a>
    </div>

    <form method="get" class="flex gap-3 mb-4 bg-white p-3 rounded border border-gray-200">
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
                    <th class="p-3 text-left">Curso</th>
                    <th class="p-3 text-left">Periodo</th>
                    <th class="p-3 text-left">Docente(s)</th>
                    <th class="p-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cursos as $curso)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3 font-semibold">{{ $curso->nombre }}</td>
                        <td class="p-3">{{ $curso->periodo->nombre }}</td>
                        <td class="p-3">
                            @forelse($curso->docentes as $docente)
                                <span
                                    class="inline-block bg-green-100 text-green-700 px-2 py-1 rounded text-xs mr-1">{{ $docente->nombreCompleto() }}</span>
                            @empty
                                <span class="text-gray-400 text-xs">Sin asignar</span>
                            @endforelse
                        </td>
                        <td class="p-3 text-center flex gap-1 justify-center">
                            <a href="{{ route('admin.cursos.editar', $curso->id) }}"
                                class="bg-yellow-500 text-white px-2 py-1 rounded text-xs hover:bg-yellow-600">Editar</a>
                            <a href="{{ route('admin.cursos.asignar-docente', $curso->id) }}"
                                class="bg-green-500 text-white px-2 py-1 rounded text-xs hover:bg-green-600">Asignar
                                Docente</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $cursos->withQueryString()->links() }}</div>
@endsection
