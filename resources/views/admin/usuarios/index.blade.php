@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-primary mb-1">Gestión de Usuarios</h1>
            <em class="text-gray-600 text-sm">Administrar estudiantes, docentes y personal</em>
        </div>
        <a href="{{ route('admin.usuarios.crear') }}"
            class="flex items-center gap-2 bg-primary text-accent px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary/90 transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nuevo Usuario
        </a>
    </div>

    <form method="get" class="bg-white rounded-sm shadow-lg p-4 mb-6 border-2 border-green-600">
        <div class="flex flex-wrap gap-3">
            <input type="text" name="buscar" value="{{ request('buscar') }}"
                placeholder="Buscar por nombre, DNI o email..."
                class="flex-1 p-2 text-sm border-2 border-gray-300 rounded-sm focus:border-primary focus:outline-none">
            <select name="rol"
                class="p-2 text-sm border-2 border-gray-300 rounded-sm focus:border-primary focus:outline-none">
                <option value="">Todos los roles</option>
                <option value="administrador" {{ request('rol') == 'administrador' ? 'selected' : '' }}>Administrador
                </option>
                <option value="docente" {{ request('rol') == 'docente' ? 'selected' : '' }}>Docente</option>
                <option value="estudiante" {{ request('rol') == 'estudiante' ? 'selected' : '' }}>Estudiante</option>
            </select>
            <select name="estado"
                class="p-2 text-sm border-2 border-gray-300 rounded-sm focus:border-primary focus:outline-none">
                <option value="">Todos los estados</option>
                <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                <option value="bloqueado" {{ request('estado') == 'bloqueado' ? 'selected' : '' }}>Bloqueado</option>
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
        @forelse ($usuarios as $usuario)
            <div class="bg-white rounded-sm shadow-lg p-6 border-2 border-gray-400 hover:shadow-2xl transition-all">
                <div class="flex justify-between items-start">
                    <div class="flex items-start gap-4 flex-1">
                        <div class="flex-1">
                            <h3 class="text-xs font-semibold text-gray-800 mb-2 uppercase">{{ $usuario->nombreCompleto() }}
                            </h3>
                            <div class="flex flex-wrap gap-2 mb-3">
                                <span
                                    class="px-3 py-1 rounded-sm text-xs font-bold border
                                    {{ $usuario->rol == 'administrador' ? 'bg-purple-100 text-purple-700 border-purple-300' : '' }}
                                    {{ $usuario->rol == 'docente' ? 'bg-green-100 text-green-700 border-green-300' : '' }}
                                    {{ $usuario->rol == 'estudiante' ? 'bg-blue-100 text-blue-700 border-blue-300' : '' }}">
                                    <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ ucfirst($usuario->rol) }}
                                </span>
                                <span
                                    class="px-3 py-1 rounded-sm text-xs font-bold border
                                    {{ $usuario->estado == 'activo' ? 'bg-green-100 text-green-700 border-green-300' : '' }}
                                    {{ $usuario->estado == 'inactivo' ? 'bg-gray-100 text-gray-700 border-gray-300' : '' }}
                                    {{ $usuario->estado == 'bloqueado' ? 'bg-red-100 text-red-700 border-red-300' : '' }}">
                                    {{ ucfirst($usuario->estado) }}
                                </span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-xs text-gray-600 px-2">
                                <div>
                                    <span class="font-semibold">DNI:</span> {{ $usuario->dni }}
                                </div>
                                <div>
                                    <span class="font-semibold">Email:</span> {{ $usuario->email }}
                                </div>
                                <div>
                                    <span class="font-semibold">Último acceso:</span>
                                    {{ $usuario->ultimo_acceso?->format('d/m/Y H:i') ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 ml-4">
                        <a href="{{ route('admin.usuarios.editar', $usuario->id) }}"
                            class="flex items-center gap-1 bg-blue-600 text-white px-3 py-2 rounded-sm text-xs font-semibold hover:bg-blue-700 transition-all shadow-md">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Editar
                        </a>
                        <form method="post" action="{{ route('admin.usuarios.toggle', $usuario->id) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="flex items-center gap-1 {{ $usuario->estado == 'activo' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-3 py-2 rounded-sm text-xs font-semibold transition-all shadow-md">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    @if ($usuario->estado == 'activo')
                                        <path fill-rule="evenodd"
                                            d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z"
                                            clip-rule="evenodd" />
                                    @else
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    @endif
                                </svg>
                                {{ $usuario->estado == 'activo' ? 'Desactivar' : 'Activar' }}
                            </button>
                        </form>
                        <form method="post" action="{{ route('admin.usuarios.reset-password', $usuario->id) }}"
                            class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="flex items-center gap-1 bg-orange-600 text-white px-3 py-2 rounded-sm text-xs font-semibold hover:bg-orange-700 transition-all shadow-md"
                                onclick="return confirm('¿Resetear contraseña?')">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Reset
                            </button>
                        </form>
                        @if ($usuario->rol == 'estudiante')
                            <a href="{{ route('admin.apoderados', $usuario->id) }}"
                                class="flex items-center gap-1 bg-indigo-600 text-white px-3 py-2 rounded-sm text-xs font-semibold hover:bg-indigo-700 transition-all shadow-md">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                </svg>
                                Apoderados
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-lg p-12 text-center border-2 border-dashed border-gray-300">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <p class="text-gray-500 font-medium text-lg">No hay usuarios registrados</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $usuarios->withQueryString()->links() }}</div>
@endsection
