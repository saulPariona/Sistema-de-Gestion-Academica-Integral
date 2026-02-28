@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-primary mb-1">Auditorías</h1>
            <p class="text-gray-600">Monitorear actividad del sistema</p>
        </div>
    </div>

    <form method="get" class="bg-white rounded-xl shadow-lg p-4 mb-6 border-2 border-primary/20">
        <div class="flex flex-wrap gap-3">
            <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por usuario o acción..."
                class="flex-1 p-2 text-sm border-2 border-gray-300 rounded-lg focus:border-primary focus:outline-none">
            <button type="submit" class="bg-primary text-accent px-6 py-2 rounded-lg text-sm font-semibold hover:bg-primary/90 transition-all shadow-md">
                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                </svg>
                Buscar
            </button>
        </div>
    </form>

    <div class="grid gap-4">
        @forelse ($auditorias as $a)
            <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-primary/20 hover:shadow-2xl transition-all">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex items-start gap-4 flex-1">
                        <div class="bg-{{ $a->accion == 'crear' ? 'green' : ($a->accion == 'actualizar' ? 'yellow' : ($a->accion == 'eliminar' ? 'red' : 'blue')) }}-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-{{ $a->accion == 'crear' ? 'green' : ($a->accion == 'actualizar' ? 'yellow' : ($a->accion == 'eliminar' ? 'red' : 'blue')) }}-600" fill="currentColor" viewBox="0 0 20 20">
                                @if($a->accion == 'crear')
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                                @elseif($a->accion == 'actualizar')
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                @elseif($a->accion == 'eliminar')
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                @else
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                @endif
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex flex-wrap gap-2 mb-2">
                                <span class="px-3 py-1 rounded-full text-xs font-bold border
                                    {{ $a->accion == 'crear' ? 'bg-green-100 text-green-700 border-green-300' : '' }}
                                    {{ $a->accion == 'actualizar' ? 'bg-yellow-100 text-yellow-700 border-yellow-300' : '' }}
                                    {{ $a->accion == 'eliminar' ? 'bg-red-100 text-red-700 border-red-300' : '' }}
                                    {{ !in_array($a->accion, ['crear', 'actualizar', 'eliminar']) ? 'bg-blue-100 text-blue-700 border-blue-300' : '' }}">
                                    {{ ucfirst($a->accion) }}
                                </span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700 border border-purple-300">
                                    {{ class_basename($a->modelo) }}
                                </span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700 border border-gray-300">
                                    ID: {{ $a->modelo_id }}
                                </span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm text-gray-600">
                                <div>
                                    <span class="font-semibold">Usuario:</span> {{ $a->usuario ? $a->usuario->nombreCompleto() : 'Sistema' }}
                                </div>
                                <div>
                                    <span class="font-semibold">Fecha:</span> {{ $a->created_at->format('d/m/Y H:i') }}
                                </div>
                                <div>
                                    <span class="font-semibold">IP:</span> {{ $a->ip }}
                                </div>
                            </div>
                            @if ($a->datos_anteriores || $a->datos_nuevos)
                                <details class="mt-3">
                                    <summary class="cursor-pointer text-blue-600 text-sm font-semibold hover:text-blue-800">Ver detalles de cambios</summary>
                                    <div class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                        @if ($a->datos_anteriores)
                                            <p class="font-semibold text-xs text-gray-700 mb-1">Antes:</p>
                                            <pre class="bg-white p-2 rounded text-xs overflow-x-auto border border-gray-200 mb-2">{{ json_encode($a->datos_anteriores, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        @endif
                                        @if ($a->datos_nuevos)
                                            <p class="font-semibold text-xs text-gray-700 mb-1">Después:</p>
                                            <pre class="bg-white p-2 rounded text-xs overflow-x-auto border border-gray-200">{{ json_encode($a->datos_nuevos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        @endif
                                    </div>
                                </details>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-lg p-12 text-center border-2 border-dashed border-gray-300">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-gray-500 font-medium text-lg">No hay registros de auditoría</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $auditorias->withQueryString()->links() }}</div>
@endsection
