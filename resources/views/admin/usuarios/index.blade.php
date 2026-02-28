@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Gestión de Usuarios</h1>
        <a href="{{ route('admin.usuarios.crear') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm font-semibold">Nuevo Usuario</a>
    </div>

    <form method="get" class="flex gap-3 mb-4 bg-white p-3 rounded border border-gray-200">
        <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por nombre, DNI o email..."
            class="grow p-2 text-sm border border-gray-300 rounded">
        <select name="rol" class="p-2 text-sm border border-gray-300 rounded">
            <option value="">Todos los roles</option>
            <option value="administrador" {{ request('rol') == 'administrador' ? 'selected' : '' }}>Administrador</option>
            <option value="docente" {{ request('rol') == 'docente' ? 'selected' : '' }}>Docente</option>
            <option value="estudiante" {{ request('rol') == 'estudiante' ? 'selected' : '' }}>Estudiante</option>
        </select>
        <select name="estado" class="p-2 text-sm border border-gray-300 rounded">
            <option value="">Todos</option>
            <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
            <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
            <option value="bloqueado" {{ request('estado') == 'bloqueado' ? 'selected' : '' }}>Bloqueado</option>
        </select>
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded text-sm">Filtrar</button>
    </form>

    <div class="bg-white rounded border border-gray-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="p-3 text-left">DNI</th>
                    <th class="p-3 text-left">Nombre Completo</th>
                    <th class="p-3 text-left">Email</th>
                    <th class="p-3 text-left">Rol</th>
                    <th class="p-3 text-left">Estado</th>
                    <th class="p-3 text-left">Último Acceso</th>
                    <th class="p-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usuarios as $usuario)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $usuario->dni }}</td>
                        <td class="p-3">{{ $usuario->nombreCompleto() }}</td>
                        <td class="p-3">{{ $usuario->email }}</td>
                        <td class="p-3">
                            <span
                                class="px-2 py-1 rounded text-xs uppercase font-semibold
                                {{ $usuario->rol == 'administrador' ? 'bg-purple-100 text-purple-700' : '' }}
                                {{ $usuario->rol == 'docente' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $usuario->rol == 'estudiante' ? 'bg-blue-100 text-blue-700' : '' }}">
                                {{ $usuario->rol }}
                            </span>
                        </td>
                        <td class="p-3">
                            <span
                                class="px-2 py-1 rounded text-xs
                                {{ $usuario->estado == 'activo' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $usuario->estado == 'inactivo' ? 'bg-gray-100 text-gray-700' : '' }}
                                {{ $usuario->estado == 'bloqueado' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ $usuario->estado }}
                            </span>
                        </td>
                        <td class="p-3 text-xs text-gray-500">
                            {{ $usuario->ultimo_acceso?->format('d/m/Y H:i') ?? '-' }}
                        </td>
                        <td class="p-3 text-center flex gap-1 justify-center">
                            <a href="{{ route('admin.usuarios.editar', $usuario->id) }}"
                                class="bg-yellow-500 text-white px-2 py-1 rounded text-xs hover:bg-yellow-600">Editar</a>
                            <form method="post" action="{{ route('admin.usuarios.toggle', $usuario->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="{{ $usuario->estado == 'activo' ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }} text-white px-2 py-1 rounded text-xs">
                                    {{ $usuario->estado == 'activo' ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                            <form method="post" action="{{ route('admin.usuarios.reset-password', $usuario->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="bg-orange-500 text-white px-2 py-1 rounded text-xs hover:bg-orange-600"
                                    onclick="return confirm('¿Resetear contraseña?')">Reset</button>
                            </form>
                            @if ($usuario->rol == 'estudiante')
                                <a href="{{ route('admin.apoderados', $usuario->id) }}"
                                    class="bg-indigo-500 text-white px-2 py-1 rounded text-xs hover:bg-indigo-600">Apoderados</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $usuarios->withQueryString()->links() }}</div>
@endsection
