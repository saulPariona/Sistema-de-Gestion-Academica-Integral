@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Nueva Matrícula</h1>
        <a href="{{ route('admin.matriculas') }}" class="text-blue-600 hover:underline text-sm">← Volver</a>
    </div>

    <div class="bg-white rounded border border-gray-200 p-6 max-w-lg">
        <form method="post" action="{{ route('admin.matriculas.guardar') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Estudiante</label>
                <select name="estudiante_id" class="w-full p-2 text-sm border border-gray-300 rounded">
                    <option value="">Seleccionar</option>
                    @foreach ($estudiantes as $e)
                        <option value="{{ $e->id }}" {{ old('estudiante_id') == $e->id ? 'selected' : '' }}>
                            {{ $e->nombreCompleto() }} - {{ $e->dni }}</option>
                    @endforeach
                </select>
                @error('estudiante_id')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Curso</label>
                <select name="curso_id" class="w-full p-2 text-sm border border-gray-300 rounded">
                    <option value="">Seleccionar</option>
                    @foreach ($cursos as $c)
                        <option value="{{ $c->id }}" {{ old('curso_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->nombre }}</option>
                    @endforeach
                </select>
                @error('curso_id')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Periodo</label>
                <select name="periodo_id" class="w-full p-2 text-sm border border-gray-300 rounded">
                    <option value="">Seleccionar</option>
                    @foreach ($periodos as $p)
                        <option value="{{ $p->id }}" {{ old('periodo_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nombre }}</option>
                    @endforeach
                </select>
                @error('periodo_id')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white px-4 py-2 rounded font-semibold hover:bg-blue-700 text-sm">Registrar
                Matrícula</button>
        </form>
    </div>
@endsection
