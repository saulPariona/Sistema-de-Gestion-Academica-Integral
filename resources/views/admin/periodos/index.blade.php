@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Periodos Académicos</h1>
        <a href="{{ route('admin.periodos.crear') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm font-semibold">Nuevo Periodo</a>
    </div>

    <div class="bg-white rounded border border-gray-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="p-3 text-left">Nombre</th>
                    <th class="p-3 text-left">Fecha Inicio</th>
                    <th class="p-3 text-left">Fecha Fin</th>
                    <th class="p-3 text-left">Estado</th>
                    <th class="p-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($periodos as $periodo)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3 font-semibold">{{ $periodo->nombre }}</td>
                        <td class="p-3">{{ $periodo->fecha_inicio->format('d/m/Y') }}</td>
                        <td class="p-3">{{ $periodo->fecha_fin->format('d/m/Y') }}</td>
                        <td class="p-3">
                            <span
                                class="px-2 py-1 rounded text-xs {{ $periodo->estado == 'activo' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $periodo->estado }}
                            </span>
                        </td>
                        <td class="p-3 text-center">
                            <a href="{{ route('admin.periodos.editar', $periodo->id) }}"
                                class="bg-yellow-500 text-white px-3 py-1 rounded text-xs hover:bg-yellow-600">Editar</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $periodos->links() }}</div>
@endsection
