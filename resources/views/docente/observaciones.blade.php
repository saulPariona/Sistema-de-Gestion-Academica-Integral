@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Observaciones - {{ $curso->nombre }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('docente.observaciones.crear', $curso) }}"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm font-semibold">Nueva
                Observación</a>
            <a href="{{ route('docente.curso', $curso) }}"
                class="text-blue-600 hover:underline text-sm py-2">← Curso</a>
        </div>
    </div>

    <div class="space-y-3">
        @forelse ($observaciones as $obs)
            <div class="bg-white rounded border border-gray-200 p-4">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">{{ $obs->estudiante->nombreCompleto() }}</p>
                        <p class="text-gray-600 text-sm mt-1">{{ $obs->texto }}</p>
                    </div>
                    <span class="text-xs text-gray-400">{{ $obs->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 py-8">No hay observaciones registradas</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $observaciones->links() }}</div>
@endsection
