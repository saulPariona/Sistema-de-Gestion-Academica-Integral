@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-primary mb-1">Estudiantes Matriculados</h1>
            <p class="text-gray-600">{{ $curso->nombre }}</p>
        </div>
        <a href="{{ route('docente.curso', $curso) }}" 
           class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver al Curso
        </a>
    </div>

    @if($curso->estudiantes->count() > 0)
        <div class="bg-white rounded-lg shadow-md p-4 mb-4 border-2 border-primary/20">
            <div class="flex items-center gap-3">
                <div class="bg-primary/10 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-primary" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total de estudiantes</p>
                    <p class="text-2xl font-bold text-primary">{{ $curso->estudiantes->count() }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg overflow-hidden border-2 border-primary/20">
        <div class="bg-gradient-to-r from-primary to-primary-dark p-4">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-accent" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-accent">Lista de Estudiantes</h2>
                    <p class="text-accent/80 text-sm">Estudiantes matriculados en este curso</p>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-primary/5">
                    <tr>
                        <th class="p-4 text-left font-bold text-primary text-sm">#</th>
                        <th class="p-4 text-left font-bold text-primary text-sm">Nombres</th>
                        <th class="p-4 text-left font-bold text-primary text-sm">Apellidos</th>
                        <th class="p-4 text-left font-bold text-primary text-sm">DNI</th>
                        <th class="p-4 text-left font-bold text-primary text-sm">Email</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($curso->estudiantes as $index => $estudiante)
                        <tr class="hover:bg-primary/5 transition-colors">
                            <td class="p-4">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-primary/10 text-primary font-bold text-sm">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    @if($estudiante->foto_perfil)
                                        <img src="{{ asset('storage/' . $estudiante->foto_perfil) }}" 
                                             alt="Foto" 
                                             class="w-10 h-10 rounded-full object-cover border-2 border-primary/30">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-primary-dark flex items-center justify-center text-accent font-bold text-sm border-2 border-white shadow">
                                            {{ strtoupper(substr($estudiante->nombres, 0, 1)) }}{{ strtoupper(substr($estudiante->apellidos, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span class="font-semibold text-gray-800">{{ $estudiante->nombres }}</span>
                                </div>
                            </td>
                            <td class="p-4 font-semibold text-gray-800">{{ $estudiante->apellidos }}</td>
                            <td class="p-4">
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold border border-blue-300">
                                    {{ $estudiante->dni }}
                                </span>
                            </td>
                            <td class="p-4 text-gray-600 text-sm">{{ $estudiante->email }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <p class="text-gray-500 font-medium text-lg">No hay estudiantes matriculados</p>
                                <p class="text-gray-400 text-sm mt-1">Los estudiantes aparecerán aquí una vez matriculados</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
