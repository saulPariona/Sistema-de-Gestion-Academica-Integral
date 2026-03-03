@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-primary mb-1">Mi Perfil</h1>
            <em class="text-gray-600 text-sm">Administra tu informacion personal</em>
        </div>
        <a href="{{ route('estudiante.dashboard') }}"
            class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Mis Cursos
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded-sm shadow-lg border-2 border-gray-400 overflow-hidden">
            <div class="bg-primary p-4">
                <div class="items-center gap-3">
                    <div>
                        <h2 class="text-md font-bold text-accent">Informacion Personal</h2>
                        <em class="text-xs text-white">Tus datos actuales</em>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-4 text-sm">
                <div class="flex items-start gap-4">
                    @if ($user->foto_perfil)
                        <img src="{{ asset('storage/' . $user->foto_perfil) }}" alt="Foto"
                            class="w-16 h-16 rounded-full object-cover border-2 border-primary shadow-md">
                    @else
                        <div class="w-16 h-16 rounded-full bg-primary flex items-center justify-center text-xs font-bold text-accent border-2 border-white shadow-md">
                            {{ strtoupper(substr($user->nombres, 0, 1)) }}{{ strtoupper(substr($user->apellidos, 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-1">
                        <p class="font-bold text-sm text-primary mb-1 uppercase">{{ $user->nombreCompleto() }}</p>
                        <p class="text-gray-600 break-all text-xs">{{ $user->email }}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-3 pt-4">
                    <div class="p-3">
                        <p class="text-xs text-gray-700 font-semibold mb-1">DNI</p>
                        <input type="text" class=" text-gray-600 w-full border border-gray-400 bg-gray-100 rounded-sm p-2 focus:outline-none" value="{{ $user->dni }}" readonly>
                    </div>
                    <div class="p-3">
                        <p class="text-xs text-gray-700 font-semibold mb-1">Telefono</p>
                        <input type="text" class="text-gray-600 w-full border border-gray-400 bg-gray-100 rounded-sm p-2 focus:outline-none " value="{{ $user->telefono ?? '-' }}" readonly>
                    </div>
                </div>
                
                <div class="p-3">
                    <p class="text-xs text-gray-700 font-semibold mb-1">Direccion</p>
                    <input type="text" class="text-gray-600 w-full border border-gray-400 bg-gray-100 rounded-sm p-2 focus:outline-none" value="{{ $user->direccion ?? '-' }}" readonly>
                </div>
                
                <div class="p-3 ">
                    <p class="text-xs text-gray-700 font-semibold mb-1">Fecha de Nacimiento</p>
                    <input type="text" class="text-gray-600 w-full border border-gray-400 bg-gray-100 rounded-sm p-2 focus:outline-none" value="{{ $user->fecha_nacimiento ? $user->fecha_nacimiento->format('d/m/Y') : '-' }}" readonly>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-sm shadow-lg border-2 border-gray-400 overflow-hidden">
            <div class="bg-primary p-4">
                <div class="items-center gap-3">
                    <div>
                        <h2 class="text-md font-bold text-accent">Actualizar Perfil</h2>
                        <em class="text-xs text-white">Modifica tus datos</em>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <form method="post" action="{{ route('estudiante.perfil.actualizar') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="text-xs text-gray-700 font-semibold mb-1">Telefono</label>
                        <input type="text" name="telefono" value="{{ old('telefono', $user->telefono) }}"
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm focus:border-primary">
                    </div>

                    <div class="mb-4">
                        <label class="text-xs text-gray-700 font-semibold mb-1">Direccion</label>
                        <input type="text" name="direccion" value="{{ old('direccion', $user->direccion) }}"
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-sm focus:border-primary uppercase">
                    </div>

                    <div class="mb-4">
                        <label class="text-xs text-gray-700 font-semibold mb-1">Foto de Perfil</label>
                        <input type="file" name="foto_perfil" accept="image/*"
                            class="block w-full text-sm text-gray-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-sm file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-accent hover:file:bg-primary cursor-pointer border-2 border-gray-200 rounded-sm">
                    </div>

                    <div class="flex gap-3 justify-center md:mt-20">
                        <button type="submit"
                            class="bg-primary text-accent px-6 py-3 rounded-sm font-bold hover:bg-primary/90 transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
