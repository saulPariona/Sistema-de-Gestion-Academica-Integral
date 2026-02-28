@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-primary mb-1">Crear Nuevo Usuario</h1>
            <p class="text-gray-600">Registra un nuevo usuario en el sistema</p>
        </div>
        <a href="{{ route('admin.usuarios') }}"
            class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Usuarios
        </a>
    </div>

    <form method="post" action="{{ route('admin.usuarios.guardar') }}" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Datos Personales --}}
            <div class="bg-white rounded-sm shadow-lg border-2 border-primary/20 p-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 border-primary/20">
                    <div class="bg-primary/10 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-primary" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-primary">Datos Personales</h2>
                        <p class="text-sm text-gray-500">Información básica del usuario</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nombres</label>
                        <input type="text" name="nombres" value="{{ old('nombres') }}"
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm" placeholder="Ingrese nombres">
                        @error('nombres')
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

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Apellidos</label>
                        <input type="text" name="apellidos" value="{{ old('apellidos') }}"
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm" placeholder="Ingrese apellidos">
                        @error('apellidos')
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

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">DNI</label>
                        <input type="text" name="dni" value="{{ old('dni') }}" maxlength="8"
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm" placeholder="12345678">
                        @error('dni')
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

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Sexo</label>
                        <select name="sexo" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm">
                            <option value="">Sin especificar</option>
                            <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                            <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono') }}"
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm" placeholder="999 999 999">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Dirección</label>
                        <input type="text" name="direccion" value="{{ old('direccion') }}"
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm" placeholder="Ingrese dirección">
                    </div>
                </div>
            </div>

            {{-- Cuenta y Acceso --}}
            <div class="space-y-6">
                <div class="bg-white rounded-sm shadow-lg border-2 border-primary/20 p-6">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 border-primary/20">
                        <div class="bg-primary/10 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-primary">Cuenta y Acceso</h2>
                            <p class="text-sm text-gray-500">Credenciales y rol del usuario</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Correo Electrónico</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm"
                                placeholder="correo@ejemplo.com">
                            @error('email')
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

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Contraseña</label>
                            <input type="password" name="password"
                                class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm"
                                placeholder="Mínimo 8 caracteres">
                            <p class="text-xs text-gray-500 mt-1.5">Mín. 8 caracteres, mayúsculas, minúsculas y números.
                            </p>
                            @error('password')
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

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Rol</label>
                            <select name="rol" id="rol-select"
                                class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm">
                                <option value="estudiante" {{ old('rol') == 'estudiante' ? 'selected' : '' }}>Estudiante
                                </option>
                                <option value="docente" {{ old('rol') == 'docente' ? 'selected' : '' }}>Docente</option>
                                <option value="administrador" {{ old('rol') == 'administrador' ? 'selected' : '' }}>
                                    Administrador</option>
                            </select>
                            @error('rol')
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
                </div>

                {{-- Información Profesional --}}
                <div class="bg-white rounded-sm shadow-lg border-2 border-primary/20 p-6">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 border-primary/20">
                        <div class="bg-primary/10 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-primary">Información Profesional</h2>
                            <p class="text-sm text-gray-500">Datos académicos y foto</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div id="campos-profesionales" class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Especialidad</label>
                                <input type="text" name="especialidad" value="{{ old('especialidad') }}"
                                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm"
                                    placeholder="Ej: Matemáticas, Ciencias...">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Grado Académico</label>
                                <input type="text" name="grado_academico" value="{{ old('grado_academico') }}"
                                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm"
                                    placeholder="Ej: Licenciado, Magíster...">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Foto de Perfil</label>
                            <input type="file" name="foto_perfil" accept="image/*"
                                class="block w-full text-sm text-gray-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-sm file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-accent hover:file:bg-primary/90 cursor-pointer border-2 border-gray-200 rounded-sm">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Botones --}}
        <div class="mt-6 flex justify-end gap-3">
            <button type="submit"
                class="flex items-center gap-2 bg-primary text-accent px-6 py-3 rounded-sm font-bold hover:bg-primary/90 transition-all shadow-md hover:shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Guardar Usuario
            </button>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rolSelect = document.getElementById('rol-select');
            const camposProfesionales = document.getElementById('campos-profesionales');

            function toggleCamposProfesionales() {
                if (rolSelect.value === 'estudiante') {
                    camposProfesionales.style.display = 'none';
                } else {
                    camposProfesionales.style.display = 'block';
                }
            }

            toggleCamposProfesionales();
            rolSelect.addEventListener('change', toggleCamposProfesionales);
        });
    </script>
@endsection
