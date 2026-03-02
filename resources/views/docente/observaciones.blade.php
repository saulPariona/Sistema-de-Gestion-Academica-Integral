@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-primary mb-1">Observaciones</h1>
            <em class="text-gray-600 text-sm">{{ $curso->nombre }}</em>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('docente.observaciones.crear', $curso) }}"
                class="flex items-center gap-2 bg-primary text-accent px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary/90 transition-all shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva Observación
            </a>
            <a href="{{ route('docente.curso', $curso) }}"
                class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Curso
            </a>
        </div>
    </div>

    <div class="grid gap-2">
        @forelse ($observaciones as $obs)
            <div class="bg-white rounded-sm shadow-lg p-6 border-2 border-gray-400 hover:shadow-2xl transition-all">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd" />
                            </svg>
                            <p class="font-bold text-gray-800">{{ $obs->estudiante->nombreCompleto() }}</p>
                        </div>
                        <p class="text-gray-600 text-sm mt-1">{{ $obs->texto }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-sm text-xs font-bold bg-gray-100 text-gray-700 border border-gray-300">
                        {{ $obs->created_at->format('d/m/Y H:i') }}
                    </span>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-sm shadow-lg p-12 text-center border-2 border-dashed border-gray-300">
                <svg class="w-8 h-8 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                <p class="text-gray-500 font-medium text-sm">No hay observaciones registradas</p>
            </div>
        @endforelse
    </div>

    @if ($observaciones->hasPages())
        <div class="mt-6">{{ $observaciones->links() }}</div>
    @endif
@endsection
