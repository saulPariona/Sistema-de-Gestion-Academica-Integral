@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-primary mb-1">{{ $curso->nombre }}</h1>
            <p class="text-gray-600">{{ $curso->periodo->nombre }}</p>
        </div>
        <a href="{{ route('estudiante.dashboard') }}" 
           class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Mis Cursos
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border-2 border-primary/20">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex items-center gap-4">
                <div class="bg-primary/10 p-4 rounded-full">
                    <svg class="w-8 h-8 text-primary" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Docente</p>
                    <p class="font-bold text-primary">{{ $curso->docentes->count() ? $curso->docentes->first()->nombreCompleto() : 'Sin asignar' }}</p>
                </div>
            </div>
            <div class="col-span-1 md:col-span-2">
                <p class="text-sm text-gray-500 mb-1">Descripción</p>
                <p class="text-gray-700">{{ $curso->descripcion ?? 'Sin descripción' }}</p>
            </div>
        </div>
    </div>

    <h2 class="text-2xl font-bold text-primary mb-4 flex items-center gap-2">
        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
        </svg>
        Exámenes Disponibles
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse ($examenes as $examen)
            <div class="bg-white rounded-xl shadow-lg p-5 border-2 border-primary/20 hover:shadow-2xl transition-all hover:-translate-y-1">
                <div class="flex justify-between items-start mb-3">
                    <div class="grow">
                        <h3 class="font-bold text-lg text-primary mb-1">{{ $examen->titulo }}</h3>
                        <p class="text-xs text-gray-500 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $examen->fecha_inicio->format('d/m/Y H:i') }} - {{ $examen->fecha_fin->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    @if ($examen->fecha_inicio <= now() && $examen->fecha_fin >= now())
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs rounded-full font-semibold">Disponible</span>
                    @else
                        <span class="px-3 py-1 bg-gray-200 text-gray-500 text-xs rounded-full font-semibold">No disponible</span>
                    @endif
                </div>
                @if ($examen->tiempo_limite)
                    <p class="text-sm text-gray-600 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        <strong>Duración:</strong> {{ $examen->tiempo_limite }} minutos
                    </p>
                @endif
                @if ($examen->fecha_inicio <= now() && $examen->fecha_fin >= now())
                    <form method="post" action="{{ route('estudiante.iniciar-examen', [$curso, $examen]) }}" class="mt-3">
                        @csrf
                        <button class="w-full bg-primary text-accent px-4 py-2.5 rounded-lg text-sm font-bold hover:bg-primary/90 transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                            </svg>
                            Rendir Examen
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <div class="col-span-2 bg-white rounded-xl shadow-lg p-12 text-center border-2 border-dashed border-gray-300">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-gray-500 font-medium">No hay exámenes disponibles en este momento</p>
            </div>
        @endforelse
    </div>
@endsection
