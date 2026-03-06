@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-primary">Apoderados</h1>
            <em class="text-gray-600 text-sm">{{ $estudiante->nombreCompleto() }}</em>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.apoderados.crear', $estudiante) }}"
                class="flex items-center gap-2 bg-primary text-accent px-4 py-2 rounded-sm text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Agregar Apoderado
            </a>
            <a href="{{ route('admin.usuarios') }}"
                class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Usuarios
            </a>
        </div>
    </div>

    <div class="bg-white rounded-sm shadow-sm border-2 border-gray-300 overflow-hidden">
        <table class="w-full text-sm">
            <thead class=" from-primary to-primary border-b-2 border-gray-300 ">
                <tr>
                    <th class="p-4 text-left font-bold text-primary text-xs">Nombre Completo</th>
                    <th class="p-4 text-left font-bold text-primary text-xs">DNI</th>
                    <th class="p-4 text-left font-bold text-primary text-xs">Teléfono</th>
                    <th class="p-4 text-left font-bold text-primary text-xs">Email</th>
                    <th class="p-4 text-left font-bold text-primary text-xs">Parentesco</th>
                    <th class="p-4 text-left font-bold text-primary text-xs">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                
                @forelse ($apoderados as $a)
                    <tr class="hover:bg-primary/5 transition-all">
                        <td class="p-4 font-medium text-gray-800">{{ $a->nombre_completo }}</td>
                        <td class="p-4 text-gray-600">{{ $a->dni }}</td>
                        <td class="p-4 text-gray-600">{{ $a->telefono ?? '-' }}</td>
                        <td class="p-4 text-gray-600">{{ $a->email ?? '-' }}</td>
                        <td class="p-4">
                            <span
                                class="px-3 py-1 rounded-full text-xs font-bold bg-primary/10 text-primary border border-primary/30">
                                {{ ucfirst($a->parentesco) }}
                            </span>
                        </td>
                        <td class="p-4">
                            <form method="post" action="{{ route('admin.apoderados.eliminar', [$estudiante, $a]) }}"
                                onsubmit="return confirm('¿Eliminar este apoderado?')">
                                @csrf
                                @method('DELETE')
                                <button
                                    class="flex items-center gap-1 bg-red-600 text-white px-3 py-2 rounded-lg text-xs font-semibold hover:bg-red-700 transition-all shadow-md">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center">
                            <svg class="w-8 h-8 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <p class="text-gray-500 font-medium text-xs">No hay apoderados registrados</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
