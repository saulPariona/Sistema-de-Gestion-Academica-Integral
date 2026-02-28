@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-primary mb-1">Mi Perfil</h1>
            <p class="text-gray-600">Administra tu información personal</p>
        </div>
        <a href="{{ route('estudiante.dashboard') }}" 
           class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Mis Cursos
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-lg border-2 border-primary/20 p-6">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 border-primary/20">
                <div class="bg-primary/10 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-primary" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-primary">Información Personal</h2>
                    <p class="text-sm text-gray-500">Tus datos actuales</p>
                </div>
            </div>
            <div class="space-y-4 text-sm">
                <div class="flex items-start gap-4">
                    @if ($user->foto_perfil)
                        <img src="{{ asset('storage/' . $user->foto_perfil) }}" alt="Foto"
                            class="w-24 h-24 rounded-full object-cover border-4 border-primary shadow-lg">
                    @else
                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-primary to-primary-dark flex items-center justify-center text-2xl font-bold text-accent border-4 border-white shadow-lg">
                            {{ strtoupper(substr($user->nombres, 0, 1)) }}{{ strtoupper(substr($user->apellidos, 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-1">
                        <p class="font-bold text-lg text-primary mb-1">{{ $user->nombreCompleto() }}</p>
                        <p class="text-gray-600 break-all">{{ $user->email }}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 pt-4">
                    <div class="bg-primary/5 p-3 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">DNI</p>
                        <p class="font-bold text-primary">{{ $user->dni }}</p>
                    </div>
                    <div class="bg-primary/5 p-3 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Teléfono</p>
                        <p class="font-bold text-primary">{{ $user->telefono ?? '-' }}</p>
                    </div>
                </div>
                
                <div class="bg-primary/5 p-3 rounded-lg">
                    <p class="text-xs text-gray-500 mb-1">Dirección</p>
                    <p class="font-bold text-primary">{{ $user->direccion ?? '-' }}</p>
                </div>
                
                <div class="bg-primary/5 p-3 rounded-lg">
                    <p class="text-xs text-gray-500 mb-1">Fecha de Nacimiento</p>
                    <p class="font-bold text-primary">{{ $user->fecha_nacimiento ? $user->fecha_nacimiento->format('d/m/Y') : '-' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border-2 border-primary/20 p-6">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 border-primary/20">
                <div class="bg-primary/10 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-primary" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-primary">Actualizar Perfil</h2>
                    <p class="text-sm text-gray-500">Modifica tus datos</p>
                </div>
            </div>
            <form method="post" action="{{ route('estudiante.perfil.actualizar') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono', $user->telefono) }}"
                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Dirección</label>
                    <input type="text" name="direccion" value="{{ old('direccion', $user->direccion) }}"
                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Foto de Perfil</label>
                    <input type="file" name="foto_perfil" accept="image/*"
                        class="block w-full text-sm text-gray-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-accent hover:file:bg-primary/90 cursor-pointer border-2 border-gray-200 rounded-lg">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nueva Contraseña 
                        <span class="text-xs text-gray-500 font-normal">(dejar vacío para no cambiar)</span>
                    </label>
                    <input type="password" name="password" 
                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                    @error('password')
                        <p class="text-red-600 text-xs mt-1 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation"
                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 bg-primary text-accent px-4 py-3 rounded-lg font-bold hover:bg-primary/90 transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Guardar Cambios
                    </button>
                    <a href="{{ route('estudiante.dashboard') }}"
                        class="px-6 py-3 border-2 border-gray-300 rounded-lg font-bold text-gray-700 hover:bg-gray-50 transition-all flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
