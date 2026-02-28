@extends('layouts.app')
@section('contenido')
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Crear Usuario</h1>

    <form method="post" action="{{ route('admin.usuarios.guardar') }}" enctype="multipart/form-data"
        class="bg-white p-6 rounded border border-gray-200 max-w-2xl">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nombres</label>
                <input type="text" name="nombres" value="{{ old('nombres') }}"
                    class="w-full p-2 text-sm border border-gray-300 rounded">
                @error('nombres')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Apellidos</label>
                <input type="text" name="apellidos" value="{{ old('apellidos') }}"
                    class="w-full p-2 text-sm border border-gray-300 rounded">
                @error('apellidos')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">DNI</label>
                <input type="text" name="dni" value="{{ old('dni') }}" maxlength="8"
                    class="w-full p-2 text-sm border border-gray-300 rounded">
                @error('dni')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Correo Electrónico</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full p-2 text-sm border border-gray-300 rounded">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Contraseña</label>
                <input type="password" name="password" class="w-full p-2 text-sm border border-gray-300 rounded">
                <p class="text-xs text-gray-500">Mín. 8 caracteres, mayúsculas, minúsculas y números.</p>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Rol</label>
                <select name="rol" class="w-full p-2 text-sm border border-gray-300 rounded">
                    <option value="estudiante" {{ old('rol') == 'estudiante' ? 'selected' : '' }}>Estudiante</option>
                    <option value="docente" {{ old('rol') == 'docente' ? 'selected' : '' }}>Docente</option>
                    <option value="administrador" {{ old('rol') == 'administrador' ? 'selected' : '' }}>Administrador
                    </option>
                </select>
                @error('rol')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha de Nacimiento</label>
                <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
                    class="w-full p-2 text-sm border border-gray-300 rounded">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Sexo</label>
                <select name="sexo" class="w-full p-2 text-sm border border-gray-300 rounded">
                    <option value="">Sin especificar</option>
                    <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                    <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Teléfono</label>
                <input type="text" name="telefono" value="{{ old('telefono') }}"
                    class="w-full p-2 text-sm border border-gray-300 rounded">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Dirección</label>
                <input type="text" name="direccion" value="{{ old('direccion') }}"
                    class="w-full p-2 text-sm border border-gray-300 rounded">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Especialidad</label>
                <input type="text" name="especialidad" value="{{ old('especialidad') }}"
                    class="w-full p-2 text-sm border border-gray-300 rounded">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Grado Académico</label>
                <input type="text" name="grado_academico" value="{{ old('grado_academico') }}"
                    class="w-full p-2 text-sm border border-gray-300 rounded">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Foto de Perfil</label>
                <input type="file" name="foto_perfil" accept="image/*"
                    class="w-full p-2 text-sm border border-gray-300 rounded">
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded font-semibold hover:bg-blue-700">Guardar</button>
            <a href="{{ route('admin.usuarios') }}"
                class="bg-gray-500 text-white px-6 py-2 rounded font-semibold hover:bg-gray-600">Cancelar</a>
        </div>
    </form>
@endsection
