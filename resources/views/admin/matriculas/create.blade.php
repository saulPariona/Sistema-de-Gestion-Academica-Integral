@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-primary mb-1">Nueva Matrícula</h1>
            <p class="text-gray-600">Registrar matrícula de estudiante</p>
        </div>
        <a href="{{ route('admin.matriculas') }}"
            class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Matrículas
        </a>
    </div>

    <div class="bg-white rounded-sm shadow-lg border-2 border-primary/20 p-6 max-w-2xl mx-auto">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 border-primary/20">
            <div class="bg-primary/10 p-3 rounded-lg">
                <svg class="w-6 h-6 text-primary" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-primary">Datos de Matrícula</h2>
                <p class="text-sm text-gray-500">Selecciona estudiante, curso y periodo</p>
            </div>
        </div>

        <form method="post" action="{{ route('admin.matriculas.guardar') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Estudiante</label>
                    <select name="estudiante_id" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm">
                        <option value="">Seleccionar estudiante</option>
                        @foreach ($estudiantes as $e)
                            <option value="{{ $e->id }}" {{ old('estudiante_id') == $e->id ? 'selected' : '' }}>
                                {{ $e->nombreCompleto() }} - {{ $e->dni }}</option>
                        @endforeach
                    </select>
                    @error('estudiante_id')
                        <p class="text-red-600 text-xs mt-1.5 flex items-center gap-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Curso</label>
                    <select name="curso_id" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm">
                        <option value="">Seleccionar curso</option>
                        @foreach ($cursos as $c)
                            <option value="{{ $c->id }}" {{ old('curso_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->nombre }}</option>
                        @endforeach
                    </select>
                    @error('curso_id')
                        <p class="text-red-600 text-xs mt-1.5 flex items-center gap-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Periodo</label>
                    <select name="periodo_id" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm">
                        <option value="">Seleccionar periodo</option>
                        @foreach ($periodos as $p)
                            <option value="{{ $p->id }}" {{ old('periodo_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nombre }}</option>
                        @endforeach
                    </select>
                    @error('periodo_id')
                        <p class="text-red-600 text-xs mt-1.5 flex items-center gap-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-center gap-3">
                <button type="submit"
                    class="flex items-center gap-2 bg-primary text-accent px-6 py-3 rounded-lg font-bold hover:bg-primary/90 transition-all shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Registrar Matrícula
                </button>
            </div>
        </form>
    </div>
@endsection
