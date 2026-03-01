@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-primary mb-1">Calificaciones</h1>
            <em class="text-gray-600 text-sm">Consultar calificaciones globales del sistema</em>
        </div>
    </div>

    <form method="get" class="bg-white rounded-sm shadow-lg p-4 mb-6 border-2 border-green-600">
        <div class="flex flex-wrap gap-3">
            <select name="curso_id"
                class="flex-1 min-w-[200px] p-2 text-sm border-2 border-gray-300 rounded-sm focus:border-primary focus:outline-none">
                <option value="">Todos los cursos</option>
                @foreach ($cursos as $curso)
                    <option value="{{ $curso->id }}" {{ request('curso_id') == $curso->id ? 'selected' : '' }}>
                        {{ $curso->nombre }}</option>
                @endforeach
            </select>
            <select name="periodo_id"
                class="flex-1 min-w-[200px] p-2 text-sm border-2 border-gray-300 rounded-sm focus:border-primary focus:outline-none">
                <option value="">Todos los periodos</option>
                @foreach ($periodos as $periodo)
                    <option value="{{ $periodo->id }}" {{ request('periodo_id') == $periodo->id ? 'selected' : '' }}>
                        {{ $periodo->nombre }}</option>
                @endforeach
            </select>
            <button type="submit"
                class="bg-primary text-accent px-6 py-2 rounded-sm text-sm font-semibold hover:bg-primary/90 transition-all shadow-md">
                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd" />
                </svg>
                Filtrar
            </button>
        </div>
    </form>

    <div class="grid gap-2">
        @forelse ($examenes as $examen)
            <div
                class="bg-white rounded-sm shadow-lg overflow-hidden border-2 border-gray-400 hover:shadow-2xl transition-all p-2">
                <div class="p-2">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-md font-bold text-primary uppercase">{{ $examen->titulo }}</h3>
                            <em class="text-gray-600 text-xs">{{ $examen->curso->nombre }}</em>
                        </div>
                        <span class="px-3 py-1 rounded-sm text-xs font-bold  text-primary">
                            Puntaje total: {{ $examen->puntaje_total }}
                        </span>
                    </div>
                </div>
                <div class="p-4">
                    <div class="bg-white rounded-sm border-2 border-gray-200 overflow-hidden">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="p-3 text-left text-xs font-bold text-gray-700">Estudiante</th>
                                    <th class="p-3 text-left text-xs font-bold text-gray-700">Intento</th>
                                    <th class="p-3 text-left text-xs font-bold text-gray-700">Puntaje</th>
                                    <th class="p-3 text-left text-xs font-bold text-gray-700">Nota (base 20)</th>
                                    <th class="p-3 text-left text-xs font-bold text-gray-700">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($examen->intentos as $intento)
                                    <tr class="hover:bg-primary/5 transition-all">
                                        <td class="p-3 text-xs text-gray-600 uppercase">
                                            {{ $intento->estudiante->nombreCompleto() }}</td>
                                        <td class="p-3">
                                            <span class="text-xs">
                                                N° {{ $intento->numero_intento }}
                                            </span>
                                        </td>
                                        <td class="p-3">
                                            <span class="text-xs">{{ $intento->puntaje_obtenido ?? '-' }}</span>
                                            <span class="text-gray-500 text-xs">/ {{ $examen->puntaje_total }}</span>
                                        </td>
                                        <td class="p-3">
                                            @if ($intento->puntaje_obtenido !== null && $examen->puntaje_total > 0)
                                                @php
                                                    $nota = ($intento->puntaje_obtenido / $examen->puntaje_total) * 20;
                                                @endphp
                                                <span
                                                    class="px-3 py-1 rounded-xs text-xs font-semibold border-2 {{ $nota >= 11 ? 'bg-green-100 text-green-800 border-green-400' : 'bg-red-100 text-red-800 border-red-400' }}">
                                                    {{ number_format($nota, 1) }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="p-3">
                                            <span
                                                class="px-3 py-1 rounded-xs text-xs font-bold border {{ $intento->estado == 'finalizado' ? 'bg-green-100 text-green-800 border-green-400' : 'bg-yellow-100 text-yellow-800 border-yellow-400' }}">
                                                {{ ucfirst($intento->estado) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-6 text-center text-gray-500">
                                            No hay intentos registrados para este examen
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-sm shadow-lg p-12 text-center border-2 border-dashed border-gray-300">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <p class="text-gray-500 font-medium text-lg">No hay calificaciones registradas</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $examenes->withQueryString()->links() }}</div>
@endsection
