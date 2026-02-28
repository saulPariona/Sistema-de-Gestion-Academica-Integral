@extends('layouts.app')
@section('contenido')
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Asignar Docente - {{ $curso->nombre }}</h1>

    <form method="post" action="{{ url('/admin/cursos/' . $curso->id . '/asignar-docente') }}"
        class="bg-white p-6 rounded border border-gray-200 max-w-lg">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700 mb-1">Docente</label>
            <select name="docente_id" class="w-full p-2 text-sm border border-gray-300 rounded">
                <option value="">Seleccionar docente</option>
                @foreach ($docentes as $docente)
                    <option value="{{ $docente->id }}">{{ $docente->nombreCompleto() }} - {{ $docente->especialidad }}
                    </option>
                @endforeach
            </select>
            @error('docente_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <p class="text-sm text-gray-600 mb-4">Docentes actuales:
            @forelse($curso->docentes as $d)
                <span class="inline-block bg-green-100 text-green-700 px-2 py-1 rounded text-xs">{{ $d->nombreCompleto() }}</span>
            @empty
                <span class="text-gray-400">Ninguno</span>
            @endforelse
        </p>
        <div class="flex gap-3">
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded font-semibold hover:bg-blue-700">Asignar</button>
            <a href="{{ route('admin.cursos') }}"
                class="bg-gray-500 text-white px-6 py-2 rounded font-semibold hover:bg-gray-600">Cancelar</a>
        </div>
    </form>
@endsection
