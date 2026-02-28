@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-primary mb-1">Mis Calificaciones</h1>
            <p class="text-gray-600">Historial de evaluaciones por curso</p>
        </div>
        <a href="{{ route('estudiante.dashboard') }}" 
           class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Mis Cursos
        </a>
    </div>

    @forelse ($calificacionesPorCurso as $data)
        <div class="bg-white rounded-xl shadow-lg mb-6 border-2 border-primary/20 overflow-hidden">
            <div class="bg-gradient-to-r from-primary to-primary-dark p-4 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-accent" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
                        </svg>
                    </div>
                    <span class="font-bold text-accent text-lg">{{ $data['curso']->nombre }}</span>
                </div>
                <div class="text-right">
                    <p class="text-xs text-accent/80 font-medium">Promedio General</p>
                    <span class="text-2xl font-bold {{ $data['promedio'] !== null && $data['promedio'] >= 11 ? 'text-green-300' : 'text-red-300' }}">
                        {{ $data['promedio'] !== null ? number_format($data['promedio'], 2) : '-' }}
                    </span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-primary/5">
                        <tr>
                            <th class="p-4 text-left font-bold text-primary text-sm">Examen</th>
                            <th class="p-4 text-center font-bold text-primary text-sm">Nota</th>
                            <th class="p-4 text-center font-bold text-primary text-sm">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($data['notas'] as $n)
                            <tr class="hover:bg-primary/5 transition-colors">
                                <td class="p-4 text-gray-800 font-medium">{{ $n['examen'] }}</td>
                                <td class="p-4 text-center">
                                    @if ($n['nota'] !== null)
                                        <span class="inline-block px-4 py-1.5 rounded-full text-sm font-bold {{ $n['nota'] >= 11 ? 'bg-green-100 text-green-700 border-2 border-green-300' : 'bg-red-100 text-red-700 border-2 border-red-300' }}">
                                            {{ number_format($n['nota'], 2) }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-sm font-medium">Sin rendir</span>
                                    @endif
                                </td>
                                <td class="p-4 text-center">
                                    @if ($n['nota'] === null)
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs bg-gray-100 text-gray-600 font-semibold border border-gray-300">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                            Pendiente
                                        </span>
                                    @elseif($n['nota'] >= 11)
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs bg-green-100 text-green-700 font-semibold border border-green-300">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Aprobado
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs bg-red-100 text-red-700 font-semibold border border-red-300">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
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
        <div class="bg-white rounded-xl shadow-lg p-12 text-center border-2 border-dashed border-gray-300">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-gray-500 font-medium text-lg">No tienes calificaciones registradas</p>
        </div>
    @endforelse
@endsection
