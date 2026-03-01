@extends('layouts.app')
@section('contenido')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-primary mb-1">Cambiar Contraseña</h1>
            <em class="text-gray-600 text-sm">Actualiza tu contraseña de acceso al sistema</em>
        </div>
        <a href="{{ url()->previous() }}"
            class="flex items-center gap-2 bg-white border-2 border-primary text-primary px-4 py-2 rounded-sm text-sm font-semibold hover:bg-primary hover:text-accent transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Formulario --}}
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
                    <h2 class="text-xl font-bold text-primary">Nueva Contraseña</h2>
                    <p class="text-sm text-gray-500">Ingresa tu contraseña actual y la nueva</p>
                </div>
            </div>

            <form method="post" action="{{ url('/change-password') }}">
                @csrf

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="current_password">Contraseña
                        Actual</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input class="w-full pl-10 pr-4 py-2.5 border-2 border-gray-200 rounded-sm" type="password"
                            name="current_password" id="current_password" placeholder="Ingresa tu contraseña actual">
                    </div>
                    @error('current_password')
                        <p class="text-red-600 text-xs mt-1.5 flex items-center gap-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="password">Nueva Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                        </div>
                        <input class="w-full pl-10 pr-4 py-2.5 border-2 border-gray-200 rounded-sm" type="password"
                            name="password" id="password" placeholder="Ingresa tu nueva contraseña">
                    </div>
                    @error('password')
                        <p class="text-red-600 text-xs mt-1.5 flex items-center gap-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="password_confirmation">Confirmar
                        Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input class="w-full pl-10 pr-4 py-2.5 border-2 border-gray-200 rounded-sm" type="password"
                            name="password_confirmation" id="password_confirmation"
                            placeholder="Repite tu nueva contraseña">
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 bg-primary text-accent px-4 py-3 rounded-sm font-bold hover:bg-primary/90 transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Cambiar Contraseña
                    </button>
                </div>
            </form>
        </div>

        {{-- Panel informativo --}}
        <div class="bg-white rounded-sm shadow-lg border-2 border-primary/20 p-6">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b-2 border-primary/20">
                <div class="bg-primary/10 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-primary" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-primary">Requisitos de Seguridad</h2>
                    <p class="text-sm text-gray-500">Tu contraseña debe cumplir lo siguiente</p>
                </div>
            </div>

            <div class="space-y-3">
                <div class="flex items-center gap-3 p-3 rounded-sm bg-primary/5 border border-primary/20">
                    <div class="bg-primary/10 p-1.5 rounded-full">
                        <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="text-sm text-gray-700 font-medium">Mínimo 8 caracteres</span>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-sm bg-primary/5 border border-primary/20">
                    <div class="bg-primary/10 p-1.5 rounded-full">
                        <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="text-sm text-gray-700 font-medium">Al menos una letra mayúscula</span>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-sm bg-primary/5 border border-primary/20">
                    <div class="bg-primary/10 p-1.5 rounded-full">
                        <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="text-sm text-gray-700 font-medium">Al menos una letra minúscula</span>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-sm bg-primary/5 border border-primary/20">
                    <div class="bg-primary/10 p-1.5 rounded-full">
                        <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 20 24">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="text-sm text-gray-700 font-medium">Al menos un número</span>
                </div>
            </div>

            <div class="mt-6 p-4 rounded-sm bg-yellow-200 border-2 border-yellow-400">
                <div class="flex items-start gap-3">
                    <div>
                        <p class="text-sm font-semibold text-yellow-900">Importante</p>
                        <p class="text-xs text-yellow-900 mt-1">Después de cambiar tu contraseña, se cerrará tu sesión y
                            deberás iniciar sesión nuevamente con la nueva contraseña.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
