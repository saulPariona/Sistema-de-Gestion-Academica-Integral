<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Colegio Max Planck</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700&family=Dancing+Script:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        .bg-primary {
            background-color: #004f39;
        }

        .bg-primary-dark {
            background-color: #151613;
        }

        .bg-accent {
            background-color: #FFFACA;
        }

        .text-primary {
            color: #004f39;
        }

        .text-accent {
            color: #FFFACA;
        }

        .border-primary {
            border-color: #004f39;
        }

        .hover-primary:hover {
            background-color: #003d2d;
        }

        .bg-pattern {
            background-image: url('{{ asset('image/fondo.png') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .bg-main {
            background: linear-gradient(rgba(229, 231, 235, 0.95), rgba(229, 231, 235, 0.95)), url('{{ asset('image/fondo.png') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
    </style>
</head>

<body class="w-full min-h-screen flex flex-col bg-main"
    @auth
@if (auth()->user()->esEstudiante() || auth()->user()->esDocente())
            <!-- Header Moderno para Estudiante/Docente -->
            <header class="bg-primary shadow-lg">
                <div class="max-w-7xl mx-auto px-2 py-2">
                    <div class="flex items-center justify-between">
                        <!-- Logo y Título -->
                        <div class="flex items-center gap-4">
                            <img src="{{ asset('image/logo_1.png') }}" alt="Logo" class="h-12 w-12 object-contain bg-white rounded-full p-1">
                            <div>
                                <h1 class="text-lg font-bold text-accent tracking-wide font-serif-accent">Colegio Max Planck</h1>
                                <p class="text-xs text-accent">Sistema de Gestión Académica</p>
                            </div>
                        </div>
                        
                        <!-- Usuario Info -->
                        <div class="flex items-center gap-4">
                            <div class="text-right hidden md:block">
                                <p class="text-sm font-semibold text-accent">{{ auth()->user()->nombreCompleto() }}</p>
                                <p class="text-xs text-accent opacity-80 uppercase">{{ auth()->user()->rol }}</p>
                            </div>
                            @if (auth()->user()->foto_perfil)
                                <img src="{{ asset('storage/' . auth()->user()->foto_perfil) }}" alt="Foto" class="w-10 h-10 rounded-full object-cover border-2 border-accent shadow-md">
                            @else
                                <div class="w-10 h-10 rounded-full bg-accent flex items-center justify-center text-primary font-bold text-lg">
                                    {{ strtoupper(substr(auth()->user()->nombres, 0, 1)) }}
                                </div>
                            @endif
                            <a class="text-sm text-primary bg-accent hover:bg-yellow-200 px-4 py-2 rounded-lg font-semibold transition-all shadow-md"
                                href="{{ route('logout') }}">Salir</a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Navegación Moderna -->
            <nav class="bg-primary-dark shadow-md">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="flex gap-2">
                        @if (auth()->user()->esDocente())
                            <a href="{{ route('docente.dashboard') }}" 
                               class="px-4 py-3 text-accent hover:bg-primary transition-all border-b-2 {{ request()->routeIs('docente.dashboard') ? 'border-accent bg-primary' : 'border-transparent' }}">
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                    </svg>
                                    Mis Cursos
                                </span>
                            </a>
                        @elseif(auth()->user()->esEstudiante())
                            <a href="{{ route('estudiante.dashboard') }}" 
                               class="px-4 py-3 text-accent hover:bg-primary transition-all border-b-2 {{ request()->routeIs('estudiante.dashboard') ? 'border-accent bg-primary' : 'border-transparent' }}">
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                    </svg>
                                    Mis Cursos
                                </span>
                            </a>
                            <a href="{{ route('estudiante.calificaciones') }}" 
                               class="px-4 py-3 text-accent hover:bg-primary transition-all border-b-2 {{ request()->routeIs('estudiante.calificaciones') ? 'border-accent bg-primary' : 'border-transparent' }}">
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                    </svg>
                                    Calificaciones
                                </span>
                            </a>
                            <a href="{{ route('estudiante.perfil') }}" 
                               class="px-4 py-3 text-accent hover:bg-primary transition-all border-b-2 {{ request()->routeIs('estudiante.perfil') ? 'border-accent bg-primary' : 'border-transparent' }}">
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    Mi Perfil
                                </span>
                            </a>
                        @endif
                        <a href="{{ route('password.change') }}" 
                           class="px-4 py-3 text-accent hover:bg-primary transition-all border-b-2 border-transparent ml-auto">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                                Contraseña
                            </span>
                        </a>
                    </div>
                </div>
            </nav>
        @elseif(auth()->user()->esAdministrador())
            <!-- Header Moderno para Administrador -->
            <header class="bg-primary shadow-lg">
                <div class="max-w-7xl mx-auto px-2 py-2">
                    <div class="flex items-center justify-between">
                        <!-- Logo y Título -->
                        <div class="flex items-center gap-4">
                            <img src="{{ asset('image/logo_1.png') }}" alt="Logo" class="h-12 w-12 object-contain bg-white rounded-full p-1">
                            <div>
                                <h1 class="text-2xl font-bold text-accent tracking-wide">Colegio Max Planck</h1>
                                <p class="text-sm text-accent">Sistema de Gestión Académica</p>
                            </div>
                        </div>
                        
                        <!-- Usuario Info -->
                        <div class="flex items-center gap-4">
                            <div class="text-right hidden md:block">
                                <p class="text-sm font-semibold text-accent">{{ auth()->user()->nombreCompleto() }}</p>
                                <p class="text-xs text-accent opacity-80 uppercase">{{ auth()->user()->rol }}</p>
                            </div>
                            @if (auth()->user()->foto_perfil)
                                <img src="{{ asset('storage/' . auth()->user()->foto_perfil) }}" alt="Foto" class="w-10 h-10 rounded-full object-cover border-2 border-accent shadow-md">
                            @else
                                <div class="w-10 h-10 rounded-full bg-accent flex items-center justify-center text-primary font-bold text-lg">
                                    {{ strtoupper(substr(auth()->user()->nombres, 0, 1)) }}
                                </div>
                            @endif
                            <a class="text-sm text-primary bg-accent hover:bg-yellow-200 px-4 py-2 rounded-lg font-semibold transition-all shadow-md"
                                href="{{ route('logout') }}">Salir</a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Navegación Moderna para Administrador -->
            <nav class="bg-primary-dark shadow-md">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="flex gap-2">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="px-4 py-3 text-accent hover:bg-primary transition-all border-b-2 {{ request()->routeIs('admin.dashboard') ? 'border-accent bg-primary' : 'border-transparent' }}">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                </svg>
                                Inicio
                            </span>
                        </a>
                        <a href="{{ route('admin.usuarios') }}" 
                           class="px-4 py-3 text-accent hover:bg-primary transition-all border-b-2 {{ request()->routeIs('admin.usuarios*') ? 'border-accent bg-primary' : 'border-transparent' }}">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                </svg>
                                Usuarios
                            </span>
                        </a>
                        <a href="{{ route('admin.periodos') }}" 
                           class="px-4 py-3 text-accent hover:bg-primary transition-all border-b-2 {{ request()->routeIs('admin.periodos*') ? 'border-accent bg-primary' : 'border-transparent' }}">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                Periodos
                            </span>
                        </a>
                        <a href="{{ route('admin.cursos') }}" 
                           class="px-4 py-3 text-accent hover:bg-primary transition-all border-b-2 {{ request()->routeIs('admin.cursos*') ? 'border-accent bg-primary' : 'border-transparent' }}">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                </svg>
                                Cursos
                            </span>
                        </a>
                        <a href="{{ route('admin.matriculas') }}" 
                           class="px-4 py-3 text-accent hover:bg-primary transition-all border-b-2 {{ request()->routeIs('admin.matriculas*') ? 'border-accent bg-primary' : 'border-transparent' }}">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                </svg>
                                Matrículas
                            </span>
                        </a>
                        <a href="{{ route('admin.calificaciones') }}" 
                           class="px-4 py-3 text-accent hover:bg-primary transition-all border-b-2 {{ request()->routeIs('admin.calificaciones*') ? 'border-accent bg-primary' : 'border-transparent' }}">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                </svg>
                                Calificaciones
                            </span>
                        </a>
                        <a href="{{ route('admin.auditorias') }}" 
                           class="px-4 py-3 text-accent hover:bg-primary transition-all border-b-2 {{ request()->routeIs('admin.auditorias*') ? 'border-accent bg-primary' : 'border-transparent' }}">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd"/>
                                </svg>
                                Auditoría
                            </span>
                        </a>
                        <a href="{{ route('password.change') }}" 
                           class="px-4 py-3 text-accent hover:bg-primary transition-all border-b-2 border-transparent ml-auto">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                                Contraseña
                            </span>
                        </a>
                    </div>
                </div>
            </nav>
        @endif @endauth
    <main class="grow w-full max-w-7xl mx-auto p-4 md:p-6">
    @if (session('status'))
        <div
            class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-r shadow-md flex items-center gap-3">
            <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            <span class="font-medium">{{ session('status') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div
            class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-r shadow-md flex items-center gap-3">
            <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd" />
            </svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    @yield('contenido')
    </main>

    @yield('scripts')
</body>

</html>
