@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Auditorías</h1>
    </div>

    <form method="get" class="flex gap-3 mb-4 bg-white p-3 rounded border border-gray-200">
        <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por usuario o acción..."
            class="p-2 text-sm border border-gray-300 rounded flex-1">
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded text-sm">Buscar</button>
    </form>

    <div class="bg-white rounded border border-gray-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="p-3 text-left">Fecha</th>
                    <th class="p-3 text-left">Usuario</th>
                    <th class="p-3 text-left">Acción</th>
                    <th class="p-3 text-left">Modelo</th>
                    <th class="p-3 text-left">ID</th>
                    <th class="p-3 text-left">IP</th>
                    <th class="p-3 text-left">Detalles</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($auditorias as $a)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3 whitespace-nowrap">{{ $a->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-3">{{ $a->usuario ? $a->usuario->nombreCompleto() : 'Sistema' }}</td>
                        <td class="p-3">
                            <span
                                class="px-2 py-1 rounded text-xs
                                @if ($a->accion == 'crear') bg-green-100 text-green-700
                                @elseif($a->accion == 'actualizar') bg-yellow-100 text-yellow-700
                                @elseif($a->accion == 'eliminar') bg-red-100 text-red-700
                                @else bg-blue-100 text-blue-700 @endif">
                                {{ $a->accion }}
                            </span>
                        </td>
                        <td class="p-3">{{ class_basename($a->modelo) }}</td>
                        <td class="p-3">{{ $a->modelo_id }}</td>
                        <td class="p-3">{{ $a->ip }}</td>
                        <td class="p-3">
                            @if ($a->datos_anteriores || $a->datos_nuevos)
                                <details class="cursor-pointer">
                                    <summary class="text-blue-600 text-xs">Ver</summary>
                                    <div class="mt-1 text-xs">
                                        @if ($a->datos_anteriores)
                                            <p class="font-semibold">Antes:</p>
                                            <pre
                                                class="bg-gray-100 p-1 rounded text-xs overflow-x-auto">{{ json_encode($a->datos_anteriores, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        @endif
                                        @if ($a->datos_nuevos)
                                            <p class="font-semibold mt-1">Después:</p>
                                            <pre
                                                class="bg-gray-100 p-1 rounded text-xs overflow-x-auto">{{ json_encode($a->datos_nuevos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        @endif
                                    </div>
                                </details>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $auditorias->withQueryString()->links() }}</div>
@endsection
