@extends('layouts.app')
@section('contenido')
    <div class="fixed inset-0 flex flex-col md:flex-row z-40">
        {{-- Panel izquierdo: formulario --}}
        <div class="w-full md:w-1/2 flex items-center justify-center bg-white overflow-y-auto">
            <div class="w-full max-w-sm px-8 py-10 md:py-0">
                {{-- Logo visible solo en móvil --}}
                <div class="flex justify-center mb-8 md:hidden">
                    <div class="flex flex-col items-center gap-2">
                        <div class="h-16 w-16 rounded-full border-2 shadow-md overflow-hidden bg-white" style="border-color: #004f39;">
                            <img src="{{ asset('image/logo_2.jpg') }}" alt="Logo" class="h-full w-full object-cover">
                        </div>
                        <div class="text-center">
                            <h1 class="text-base font-bold font-serif" style="color: #004f39;">Colegio Max Planck</h1>
                            <p class="text-[10px] text-gray-500 font-serif">Sistema de Gestión Académica</p>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-3xl font-bold font-serif" style="color: #004f39;">Recuperar Contraseña</h2>
                    <p class="text-sm text-gray-500 mt-2 font-serif">Ingresa tu correo y te enviaremos un enlace para restablecer tu contraseña</p>
                </div>

                <form method="post" action="{{ route('password.email') }}">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 font-serif" for="email">Correo Electrónico</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </span>
                            <input class="w-full pl-10 pr-4 py-2.5 text-sm border-2 border-gray-200 rounded-sm focus:border-green-700 focus:outline-none transition-colors bg-gray-50 focus:bg-white"
                                type="email" value="{{ old('email') }}" name="email" id="email"
                                placeholder="correo@colegiomp.edu.pe">
                        </div>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full py-2.5 rounded-sm text-white bg-primary font-semibold text-sm shadow-lg cursor-pointer font-serif mb-4">
                        Enviar enlace de recuperación
                    </button>
                </form>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-xs font-medium hover:underline transition-colors inline-flex items-center gap-1" style="color: #078461;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver al inicio de sesión
                    </a>
                </div>
            </div>
        </div>

        {{-- Panel derecho: ilustración --}}
        <div class="hidden md:flex md:w-1/2 relative overflow-hidden" style="background: linear-gradient(135deg, #004f39 0%, #078461 50%, #004f39 100%);">
            {{-- Forma decorativa SVG --}}
            <svg class="absolute inset-0 w-full h-full opacity-10" viewBox="0 0 800 800" preserveAspectRatio="xMidYMid slice">
                <circle cx="400" cy="400" r="350" fill="none" stroke="white" stroke-width="1"/>
                <circle cx="400" cy="400" r="280" fill="none" stroke="white" stroke-width="0.5"/>
                <circle cx="400" cy="400" r="200" fill="none" stroke="white" stroke-width="0.5"/>
            </svg>

            {{-- Logo superior --}}
            <div class="absolute top-8 right-8 flex items-center gap-3 z-10">
                <div class="text-right">
                    <h1 class="text-xl font-bold text-white tracking-wide font-serif">Colegio Max Planck</h1>
                    <p class="text-xs text-white/70 font-serif">Sistema de Gestión Académica</p>
                </div>
                <div class="h-14 w-14 rounded-full border-2 border-white/30 shadow-lg overflow-hidden bg-white">
                    <img src="{{ asset('image/logo_2.jpg') }}" alt="Logo" class="h-full w-full object-cover">
                </div>
            </div>

            {{-- Imagen del árbol centrada --}}
            <div class="flex items-center justify-center w-full h-full p-16">
                <div class="relative">
                    <img src="{{ asset('image/arbol.jpg') }}" alt="Ilustración" class="max-h-[60vh] w-auto object-contain drop-shadow-2xl" style="mix-blend-mode: multiply; filter: brightness(1.1) contrast(1.05);">
                </div>
            </div>

            {{-- Texto inferior --}}
            <div class="absolute bottom-8 left-8 right-8 z-10">
                <p class="text-white/60 text-xs font-serif text-center">&copy; {{ date('Y') }} Colegio Max Planck. Todos los derechos reservados.</p>
            </div>
        </div>
    </div>
@endsection
