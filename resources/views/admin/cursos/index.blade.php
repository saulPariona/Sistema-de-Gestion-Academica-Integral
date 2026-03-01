@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-primary mb-1">Gestión de Cursos</h1>
            <em class="text-gray-600 text-sm">Administrar cursos y asignar docentes</em>
        </div>
        <a href="{{ route('admin.cursos.crear') }}"
            class="flex items-center gap-2 bg-primary text-accent px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary/90 transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nuevo Curso
        </a>
    </div>

    <form method="get" class="bg-white rounded-sm shadow-lg p-4 mb-6 border-2 border-green-600">
        <div class="flex flex-wrap gap-3">
            <select name="periodo_id"
                class="flex-1 p-2 text-sm border-2 border-gray-300 rounded-sm focus:border-primary focus:outline-none">
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
        @forelse ($cursos as $curso)
            <div class="bg-white rounded-sm shadow-lg p-6 border-2 border-gray-400 hover:shadow-2xl transition-all">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $curso->nombre }}</h3>
                        @if ($curso->descripcion)
                            <p class="text-gray-600 text-xs mb-3">{{ $curso->descripcion }}</p>
                        @endif
                        <div class="flex flex-wrap gap-2 mb-3">
                            <span
                                class="px-3 py-1 rounded-sm text-xs font-bold bg-orange-100 text-orange-700 border border-orange-300">
                                <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $curso->periodo->nombre }}
                            </span>
                            @forelse($curso->docentes as $docente)
                                <span
                                    class="px-3 py-1 rounded-sm text-xs font-bold bg-green-100 text-green-700 border border-green-300">
                                    <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                                    </svg>
                                    {{ $docente->nombreCompleto() }}
                                </span>
                            @empty
                                <span
                                    class="px-3 py-1 rounded-sm text-xs font-bold bg-red-100 text-red-700 border border-red-300">Sin
                                    docente asignado</span>
                            @endforelse
                        </div>
                    </div>
                    <div class="flex gap-2 ml-4">
                        <a href="{{ route('admin.cursos.editar', $curso->id) }}"
                            class="flex items-center gap-1 bg-blue-600 text-white px-3 py-2 rounded-sm text-xs font-semibold hover:bg-blue-700 transition-all shadow-md">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Editar
                        </a>
                        <a href="{{ route('admin.cursos.asignar-docente', $curso->id) }}"
                            class="flex items-center gap-1 bg-green-600 text-white px-3 py-2 rounded-sm text-xs font-semibold hover:bg-green-700 transition-all shadow-md">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                            </svg>
                            Asignar Docente
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-sm shadow-lg p-12 text-center border-2 border-dashed border-gray-300">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <p class="text-gray-500 font-medium text-lg">No hay cursos registrados</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $cursos->withQueryString()->links() }}</div>
@endsection
