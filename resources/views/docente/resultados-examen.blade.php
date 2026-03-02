@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-primary mb-1">Resultados</h1>
            <em class="text-gray-600 text-sm">{{ $examen->titulo }}</em>
        </div>
        <a href="{{ route('docente.examenes', $curso) }}"
            class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver
        </a>
    </div>

    <div class="bg-white rounded-sm shadow-lg mb-6 p-3 border-2 border-green-600">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="flex items-center gap-3">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Curso</p>
                    <p class="font-bold text-xs text-gray-800">{{ $curso->nombre }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Puntaje Total</p>
                    <p class="font-bold text-xs text-gray-800">{{ $examen->puntaje_total }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Intentos</p>
                    <p class="font-bold text-xs text-gray-800">{{ $examen->intentos->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-sm shadow-lg overflow-hidden border-2 border-gray-300">
        <table class="w-full">
            <thead class="bg-primary text-white">
                <tr>
                    <th class="p-4 text-left font-bold text-xs">Estudiante</th>
                    <th class="p-4 text-left font-bold text-xs">Intento</th>
                    <th class="p-4 text-left font-bold text-xs">Inicio</th>
                    <th class="p-4 text-left font-bold text-xs">Fin</th>
                    <th class="p-4 text-left font-bold text-xs">Puntaje</th>
                    <th class="p-4 text-left font-bold text-xs">Nota (base 20)</th>
                    <th class="p-4 text-left font-bold text-xs">Estado</th>
                    <th class="p-4 text-left font-bold text-xs">Detalle</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($examen->intentos as $intento)
                    <tr class="hover:bg-primary/5 transition-all">
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <span
                                    class="font-medium text-xs text-gray-800 uppercase">{{ $intento->estudiante->nombreCompleto() }}</span>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="text-xs">
                                N° {{ $intento->numero_intento }}
                            </span>
                        </td>
                        <td class="p-4 text-xs">{{ $intento->inicio->format('d/m/Y H:i') }}</td>
                        <td class="p-4 text-xs">{{ $intento->fin ? $intento->fin->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td class="p-4">
                            <span class="font-bold text-xs">{{ $intento->puntaje_obtenido ?? '-' }}</span>
                            <span class="text-gray-500 text-xs">/ {{ $examen->puntaje_total }}</span>
                        </td>
                        <td class="p-4">
                            @if ($intento->puntaje_obtenido !== null && $examen->puntaje_total > 0)
                                @php
                                    $nota = ($intento->puntaje_obtenido / $examen->puntaje_total) * 20;
                                @endphp
                                <span
                                    class="px-2 py-1 rounded-xs text-xs font-semibold {{ $nota >= 11 ? 'text-green-700' : 'text-red-700' }}">
                                    {{ number_format($nota, 1) }}
                                </span>
                            @else
                                <span class="text-gray-400 font-medium text-xs">-</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <span
                                class="px-2 py-1 rounded-xs text-xs font-semibold {{ $intento->estado == 'finalizado' ? 'text-green-700' : 'text-yellow-700' }}">
                                {{ ucfirst($intento->estado) }}
                            </span>
                        </td>
                        <td class="p-2">
                            <a href="{{ route('docente.examenes.resultado-estudiante', [$curso, $examen, $intento]) }}"
                                class="bg-blue-500 text-white  px-2 py-2 rounded-xs text-xs font-semibold hover:bg-blue-600 transition-all shadow-md cursor-pointer border-2 border-blue-800">
                                Ver Más
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-12">
                            <div class="text-center">
                                <svg class="w-8 h-8 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="text-gray-500 font-medium text-xs">No hay intentos registrados</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
