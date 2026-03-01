@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-primary mb-1">Asignar Docente</h1>
            <p class="text-gray-600">{{ $curso->nombre }}</p>
        </div>
        <a href="{{ route('admin.cursos') }}"
            class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Cursos
        </a>
    </div>

    <div class="bg-white rounded-sm shadow-lg border-2  p-6 max-w-2xl mx-auto">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 ">
            <div class="bg-primary/10 p-3 rounded-lg">
                <svg class="w-6 h-6 text-primary" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-primary">Seleccionar Docente</h2>
                <p class="text-sm text-gray-500">Asigna un docente al curso</p>
            </div>
        </div>

        {{-- Docentes actuales --}}
        <div class="mb-6 p-4 bg-primary/5 rounded-sm border-2 border-gray-300">
            <p class="text-sm font-semibold text-gray-700 mb-2">Docentes actuales:</p>
            <div class="flex flex-wrap gap-2">
                @forelse($curso->docentes as $d)
                    <span
                        class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-300">
                        <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ $d->nombreCompleto() }}
                    </span>
                @empty
                    <span class="text-sm text-gray-400 italic">Ninguno asignado</span>
                @endforelse
            </div>
        </div>

        <form method="post" action="{{ url('/admin/cursos/' . $curso->id . '/asignar-docente') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Docente</label>
                    <select name="docente_id"
                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm">
                        <option value="">Seleccionar docente</option>
                        @foreach ($docentes as $docente)
                            <option value="{{ $docente->id }}">{{ $docente->nombreCompleto() }} -
                                {{ $docente->especialidad }}</option>
                        @endforeach
                    </select>
                    @error('docente_id')
                        <p class="text-red-600 text-xs mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-center">
                <button type="submit"
                    class="flex items-center gap-2 bg-primary text-accent px-6 py-3 rounded-sm font-bold hover:bg-primary/90 transition-all shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Asignar
                </button>
            </div>
        </form>
    </div>
@endsection
