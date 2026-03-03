@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-primary mb-1">Mis Calificaciones</h1>
            <em class="text-gray-600 text-sm">Historial de evaluaciones por curso</em>
        </div>
        <a href="{{ route('estudiante.dashboard') }}"
            class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Mis Cursos
        </a>
    </div>

    @forelse ($calificacionesPorCurso as $data)
        <div class="bg-white rounded-sm shadow-lg mb-4 border-2 border-gray-400 overflow-hidden">
            <div class="bg-primary p-4 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="bg-accent/20 p-2 rounded-sm">
                        <svg class="w-5 h-5 text-accent" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
                        </svg>
                    </div>
                    <span class="font-bold text-accent text-sm uppercase">{{ $data['curso']->nombre }}</span>
                </div>
                <div class="text-right">
                    <p class="text-xs text-white font-medium">Promedio General</p>
                    <span class="text-sm  {{ $data['promedio'] !== null && $data['promedio'] >= 11 ? 'text-white' : 'text-red-500' }}">
                        {{ $data['promedio'] !== null ? number_format($data['promedio'], 2) : '-' }}
                    </span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-primary/5">
                        <tr>
                            <th class="p-4 text-left font-bold text-primary text-xs">Examen</th>
                            <th class="p-4 text-center font-bold text-primary text-xs">Nota</th>
                            <th class="p-4 text-center font-bold text-primary text-xs">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($data['notas'] as $n)
                            <tr class="transition-colors">
                                <td class="p-4 text-gray-800 text-xs uppercase">{{ $n['examen'] }}</td>
                                <td class="p-4 text-center">
                                    @if ($n['nota'] !== null)
                                        <span class="inline-block px-3 py-1 rounded-sm text-xs  {{ $n['nota'] >= 11 ? ' text-green-700' : ' text-red-700' }}">
                                            {{ number_format($n['nota'], 2) }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">Sin rendir</span>
                                    @endif
                                </td>
                                <td class="p-4 text-center">
                                    @if ($n['nota'] === null)
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-sm text-xs bg-gray-100 text-gray-600  border border-gray-300">
                                            Pendiente
                                        </span>
                                    @elseif($n['nota'] >= 11)
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-sm text-xs bg-green-100 text-green-700  border border-green-300">
                                            Aprobado
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-sm text-xs bg-red-100 text-red-700 border border-red-300">
                                            Desaprobado
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-sm shadow-lg p-12 text-center border-2 border-dashed border-gray-300">
            <svg class="w-8 h-8 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-gray-500 font-medium text-sm">No tienes calificaciones registradas</p>
        </div>
    @endforelse
@endsection
