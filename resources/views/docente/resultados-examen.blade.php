@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-primary mb-1">Resultados - Examen de ingreso</h1>
            <p class="text-gray-600">{{ $examen->titulo }}</p>
        </div>
        <a href="{{ route('docente.examenes', $curso) }}" 
            class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg mb-6 p-6 border-2 border-primary/20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="flex items-center gap-3">
                <div class="bg-primary/10 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Curso</p>
                    <p class="font-bold text-lg text-gray-800">{{ $curso->nombre }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Puntaje Total</p>
                    <p class="font-bold text-lg text-gray-800">{{ $examen->puntaje_total }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Intentos</p>
                    <p class="font-bold text-lg text-gray-800">{{ $examen->intentos->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden border-2 border-primary/20">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-primary to-primary/80 text-white">
                <tr>
                    <th class="p-4 text-left font-bold">Estudiante</th>
                    <th class="p-4 text-left font-bold">Intento #</th>
                    <th class="p-4 text-left font-bold">Inicio</th>
                    <th class="p-4 text-left font-bold">Fin</th>
                    <th class="p-4 text-left font-bold">Puntaje</th>
                    <th class="p-4 text-left font-bold">Nota (base 20)</th>
                    <th class="p-4 text-left font-bold">Estado</th>
                    <th class="p-4 text-left font-bold">Detalle</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($examen->intentos as $intento)
                    <tr class="hover:bg-primary/5 transition-all">
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <div class="bg-primary/10 p-2 rounded-full">
                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <span class="font-medium text-gray-800">{{ $intento->estudiante->nombreCompleto() }}</span>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-300">
                                #{{ $intento->numero_intento }}
                            </span>
                        </td>
                        <td class="p-4 text-sm text-gray-600">{{ $intento->inicio->format('d/m/Y H:i') }}</td>
                        <td class="p-4 text-sm text-gray-600">{{ $intento->fin ? $intento->fin->format('d/m/Y H:i') : '-' }}</td>
                        <td class="p-4">
                            <span class="font-bold text-gray-800">{{ $intento->puntaje_obtenido ?? '-' }}</span>
                            <span class="text-gray-500">/ {{ $examen->puntaje_total }}</span>
                        </td>
                        <td class="p-4">
                            @if ($intento->puntaje_obtenido !== null && $examen->puntaje_total > 0)
                                @php
                                    $nota = ($intento->puntaje_obtenido / $examen->puntaje_total) * 20;
                                @endphp
                                <span class="px-3 py-1 rounded-full text-sm font-bold border-2 {{ $nota >= 11 ? 'bg-green-100 text-green-700 border-green-300' : 'bg-red-100 text-red-700 border-red-300' }}">
                                    {{ number_format($nota, 1) }}
                                </span>
                            @else
                                <span class="text-gray-400 font-medium">-</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $intento->estado == 'finalizado' ? 'bg-green-100 text-green-700 border-green-300' : 'bg-yellow-100 text-yellow-700 border-yellow-300' }}">
                                {{ ucfirst($intento->estado) }}
                            </span>
                        </td>
                        <td class="p-4">
                            <a href="{{ route('docente.examenes.resultado-estudiante', [$curso, $examen, $intento]) }}"
                                class="flex items-center gap-1 text-blue-600 hover:text-blue-800 font-semibold text-sm transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Ver Detalle
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-12">
                            <div class="text-center">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="text-gray-500 font-medium text-lg">No hay intentos registrados</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
