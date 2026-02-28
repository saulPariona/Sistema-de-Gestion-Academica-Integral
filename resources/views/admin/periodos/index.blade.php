@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-primary mb-1">Periodos Académicos</h1>
            <p class="text-gray-600">Configurar y gestionar periodos escolares</p>
        </div>
        <a href="{{ route('admin.periodos.crear') }}"
            class="flex items-center gap-2 bg-primary text-accent px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary/90 transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nuevo Periodo
        </a>
    </div>

    <div class="grid gap-4">
        @forelse ($periodos as $periodo)
            <div
                class="bg-white rounded-sm shadow-lg p-6 border-2 {{ $periodo->estado == 'activo' ? 'border-green-300' : 'border-gray-400' }} hover:shadow-2xl transition-all">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <h3 class="text-xl font-bold text-gray-800">{{ $periodo->nombre }}</h3>
                            <span
                                class="px-3 py-1 rounded-sm text-xs font-bold border {{ $periodo->estado == 'activo' ? 'bg-green-100 text-green-700 border-green-300' : 'bg-gray-100 text-gray-700 border-gray-300' }}">
                                {{ ucfirst($periodo->estado) }}
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-4 text-xs text-gray-600">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span><span class="font-semibold">Inicio:</span>
                                    {{ $periodo->fecha_inicio->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span><span class="font-semibold">Fin:</span>
                                    {{ $periodo->fecha_fin->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.periodos.editar', $periodo->id) }}"
                        class="flex items-center gap-1 bg-blue-600 text-white px-3 py-2 rounded-sm text-xs font-semibold hover:bg-blue-700 transition-all shadow-md">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar
                    </a>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-sm shadow-lg p-12 text-center border-2 border-dashed border-gray-300">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-gray-500 font-medium text-lg">No hay periodos registrados</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $periodos->links() }}</div>
@endsection
