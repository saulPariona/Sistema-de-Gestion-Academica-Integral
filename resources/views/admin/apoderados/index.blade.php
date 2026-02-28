@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Apoderados de {{ $estudiante->nombreCompleto() }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.apoderados.crear', $estudiante) }}"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm font-semibold">Agregar
                Apoderado</a>
            <a href="{{ route('admin.usuarios') }}" class="text-blue-600 hover:underline text-sm py-2">← Usuarios</a>
        </div>
    </div>

    <div class="bg-white rounded border border-gray-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="p-3 text-left">Nombre Completo</th>
                    <th class="p-3 text-left">DNI</th>
                    <th class="p-3 text-left">Teléfono</th>
                    <th class="p-3 text-left">Email</th>
                    <th class="p-3 text-left">Parentesco</th>
                    <th class="p-3 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($apoderados as $a)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $a->nombre_completo }}</td>
                        <td class="p-3">{{ $a->dni }}</td>
                        <td class="p-3">{{ $a->telefono ?? '-' }}</td>
                        <td class="p-3">{{ $a->email ?? '-' }}</td>
                        <td class="p-3">{{ $a->parentesco }}</td>
                        <td class="p-3">
                            <form method="post"
                                action="{{ route('admin.apoderados.eliminar', [$estudiante, $a]) }}"
                                onsubmit="return confirm('¿Eliminar este apoderado?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline text-xs">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-3 text-center text-gray-500">No hay apoderados registrados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
