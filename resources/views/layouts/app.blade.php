<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Colegio Max Planck</title>
    @vite('resources/css/app.css')
    <style>
        .bg-primary { background-color: #004f39; }
        .bg-primary-dark { background-color: #151613; }
        .bg-accent { background-color: #FFFACA; }
        .text-primary { color: #004f39; }
        .text-accent { color: #FFFACA; }
        .border-primary { border-color: #004f39; }
        .hover-primary:hover { background-color: #003d2d; }
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
        @if(auth()->user()->esEstudiante() || auth()->user()->esDocente())
            <!-- Header Moderno para Estudiante/Docente -->
            <header class="bg-primary shadow-lg">
                <div class="max-w-7xl mx-auto px-4 py-4">
                    <div class="flex items-center justify-between">
                        <!-- Logo y Título -->
                        <div class="flex items-center gap-4">
                            <img src="{{ asset('image/logo_1.png') }}" alt="Logo" class="h-14 w-14 object-contain bg-white rounded-full p-1">
                            <div>
                                <h1 class="text-2xl font-bold text-accent tracking-wide">Colegio Max Planck</h1>
                                <p class="text-sm text-accent opacity-80">Sistema de Gestión Académica</p>
                            </div>
                        </div>
                        
                        <!-- Usuario Info -->
                        <div class="flex items-center gap-4">
                            <div class="text-right hidden md:block">
                                <p class="text-sm font-semibold text-accent">{{ auth()->user()->nombreCompleto() }}</p>
                                <p class="text-xs text-accent opacity-80 uppercase">{{ auth()->user()->rol }}</p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-accent flex items-center justify-center text-primary font-bold text-lg">
                                {{ strtoupper(substr(auth()->user()->nombres, 0, 1)) }}
                            </div>
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
                        @if(auth()->user()->esDocente())
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
        @else
            <!-- Header Original para Administrador -->
            <header class="p-3 bg-blue-700 shadow-md shadow-gray-300">
                <div class="max-w-7xl mx-auto flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-bold text-white uppercase">Colegio Max Planck</h1>
                        <p class="text-sm text-blue-200">Sistema de Gestión Académica</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-white">{{ auth()->user()->nombreCompleto() }}</span>
                        <span class="text-xs bg-blue-500 text-white px-2 py-1 rounded uppercase">{{ auth()->user()->rol }}</span>
                        <a class="text-sm text-white bg-red-500 hover:bg-red-600 px-3 py-1 rounded font-semibold"
                            href="{{ route('logout') }}">Salir</a>
                    </div>
                </div>
            </header>

            <nav class="bg-blue-800 text-white text-sm">
                <div class="max-w-7xl mx-auto flex gap-1 px-4">
                    <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 hover:bg-blue-600">Inicio</a>
                    <a href="{{ route('admin.usuarios') }}" class="px-3 py-2 hover:bg-blue-600">Usuarios</a>
                    <a href="{{ route('admin.periodos') }}" class="px-3 py-2 hover:bg-blue-600">Periodos</a>
                    <a href="{{ route('admin.cursos') }}" class="px-3 py-2 hover:bg-blue-600">Cursos</a>
                    <a href="{{ route('admin.matriculas') }}" class="px-3 py-2 hover:bg-blue-600">Matrículas</a>
                    <a href="{{ route('admin.calificaciones') }}" class="px-3 py-2 hover:bg-blue-600">Calificaciones</a>
                    <a href="{{ route('admin.auditorias') }}" class="px-3 py-2 hover:bg-blue-600">Auditoría</a>
                    <a href="{{ route('password.change') }}" class="px-3 py-2 hover:bg-blue-600 ml-auto">Cambiar Contraseña</a>
                </div>
            </nav>
        @endif
    @endauth

    <main class="grow w-full max-w-7xl mx-auto p-4 md:p-6">
        @if (session('status'))
            <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-r shadow-md flex items-center gap-3">
                <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="font-medium">{{ session('status') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-r shadow-md flex items-center gap-3">
                <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        @yield('contenido')
    </main>

    @auth
        @if(auth()->user()->esEstudiante() || auth()->user()->esDocente())
            <footer class="bg-primary-dark text-accent py-6 mt-8">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('image/logo_1.png') }}" alt="Logo" class="h-10 w-10 object-contain bg-white rounded-full p-1">
                            <div>
                                <p class="font-bold text-lg">Colegio Max Planck</p>
                                <p class="text-sm opacity-80">Excelencia Educativa</p>
                            </div>
                        </div>
                        <div class="text-center md:text-right">
                            <p class="text-sm opacity-90">&copy; {{ date('Y') }} Todos los derechos reservados</p>
                            <p class="text-xs opacity-70 mt-1">Sistema de Gestión Académica v1.0</p>
                        </div>
                    </div>
                </div>
            </footer>
        @else
            <footer class="p-3 bg-blue-700 text-center">
                <p class="text-sm text-white">Colegio Max Planck &copy; {{ date('Y') }}</p>
            </footer>
        @endif
    @endauth

    @yield('scripts')
</body>

</html>
