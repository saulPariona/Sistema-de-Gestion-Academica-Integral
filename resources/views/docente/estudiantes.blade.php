@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-primary mb-1">Estudiantes Matriculados</h1>
            <em class="text-gray-600 text-sm">{{ $curso->nombre }}</em>
        </div>
        <a href="{{ route('docente.curso', $curso) }}"
            class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver al Curso
        </a>
    </div>

    @if ($curso->estudiantes->count() > 0)
        <div class="bg-white rounded-sm shadow-lg p-1 mb-2 border-3 border-primary">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-sm">
                    <svg class="w-6 h-6 text-primary" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                </div>
                <div class="flex items-center gap-1">
                    <p class="text-sm text-gray-600">Total de estudiantes:</p>
                    <p class="text-sm text-gray-600 font-bold">{{ $curso->estudiantes->count() }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-sm shadow-lg overflow-hidden border-2 border-gray-400">
        <div class="bg-primary p-4">
            <div class="flex items-center gap-3">
                <div>
                    <h2 class="text-md font-bold text-accent">Lista de Estudiantes</h2>
                    <em class="text-white text-xs">Estudiantes matriculados en este curso</em>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-primary/5">
                    <tr>
                        <th class="p-4 text-left font-bold text-primary text-xs">N°</th>
                        <th class="p-4 text-left font-bold text-primary text-xs">Foto</th>
                        <th class="p-4 text-left font-bold text-primary text-xs">Nombres</th>
                        <th class="p-4 text-left font-bold text-primary text-xs">Apellidos</th>
                        <th class="p-4 text-left font-bold text-primary text-xs">DNI</th>
                        <th class="p-4 text-left font-bold text-primary text-xs">Email</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($curso->estudiantes as $index => $estudiante)
                        <tr class="hover:bg-primary/5 transition-colors">
                            <td class="p-2">
                                <span
                                    class="inline-flex items-center justify-center w-8 rounded-sm text-primary font-bold text-sm">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    @if ($estudiante->foto_perfil)
                                        <img src="{{ asset('storage/' . $estudiante->foto_perfil) }}" alt="Foto"
                                            class="w-8 h-8 rounded-sm object-cover border">
                                    @else
                                        <div
                                            class="w-8 h-8 rounded-sm bg-primary flex items-center justify-center text-accent font-bold text-sm border-2 border-white shadow">
                                            {{ strtoupper(substr($estudiante->nombres, 0, 1)) }}{{ strtoupper(substr($estudiante->apellidos, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="p-4 text-gray-800 text-xs uppercase">{{ $estudiante->nombres }}</td>
                            <td class="p-4 text-gray-800 text-xs uppercase">{{ $estudiante->apellidos }}</td>
                            <td class="p-4 text-gray-800 text-xs uppercase">{{ $estudiante->dni }}</td>
                            <td class="p-4 text-gray-800 text-xs">{{ $estudiante->email }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center">
                                <p class="text-gray-500 font-medium text-lg">No hay estudiantes matriculados</p>
                                <p class="text-gray-400 text-sm mt-1">Los estudiantes aparecerán aquí una vez matriculados
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
