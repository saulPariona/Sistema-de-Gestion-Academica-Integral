@extends('layouts.app')
@section('contenido')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-primary mb-1">Panel de Administración</h1>
        <em class="text-gray-600 text-sm">Sistema de Gestión Académica</em>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-md shadow-2xl p-6 border-2 border-green-600">
            <div class="flex items-center gap-4">
                <div class="bg-green-100 p-4 rounded-md">
                    <svg class="w-8 h-8 text-green-800" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium uppercase">Estudiantes</p>
                    <p class="text-3xl font-bold text-green-800">{{ $totalEstudiantes }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-md shadow-2xl p-6 border-2 border-green-600">
            <div class="flex items-center gap-4">
                <div class="bg-green-100 p-4 rounded-md">
                    <svg class="w-8 h-8 text-green-800" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium uppercase">Docentes</p>
                    <p class="text-3xl font-bold text-green-800">{{ $totalDocentes }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-md shadow-2xl p-6 border-2 border-green-600">
            <div class="flex items-center gap-4">
                <div class="bg-green-100 p-4 rounded-md">
                    <svg class="w-8 h-8 text-green-800" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium uppercase">Cursos</p>
                    <p class="text-3xl font-bold text-green-800">{{ $totalCursos }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-md shadow-2xl p-6 border-2 border-green-600">
            <div class="flex items-center gap-4">
                <div class="bg-green-100 p-4 rounded-md">
                    <svg class="w-8 h-8 text-green-800" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium uppercase">Periodo Activo</p>
                    <p class="text-lg font-bold text-green-800">{{ $periodoActivo?->nombre ?? 'Ninguno' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <a href="{{ route('admin.usuarios') }}"
            class="bg-white rounded-sm shadow-lg p-6 border-2 border-gray-400 hover:shadow-2xl transition-all group">
            <div class="flex items-start gap-4">
                <div class="p-3 rounded-lg group-hover:bg-primary group-hover:text-accent transition-all">
                    <svg class="w-6 h-6 text-primary group-hover:text-accent" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-lg text-gray-800 mb-1">Gestionar Usuarios</p>
                    <p class="text-sm text-gray-600">Crear, editar, activar/desactivar usuarios</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.cursos') }}"
            class="bg-white rounded-sm shadow-lg p-6 border-2 border-gray-400 hover:shadow-2xl transition-all group">
            <div class="flex items-start gap-4">
                <div class="p-3 rounded-lg group-hover:bg-primary group-hover:text-accent transition-all">
                    <svg class="w-6 h-6 text-primary group-hover:text-accent" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-lg text-gray-800 mb-1">Gestionar Cursos</p>
                    <p class="text-sm text-gray-600">Administrar cursos y asignar docentes</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.matriculas') }}"
            class="bg-white rounded-md shadow-lg p-6 border-2 border-gray-400 hover:shadow-2xl transition-all group">
            <div class="flex items-start gap-4">
                <div class="bg-primary/10 p-3 rounded-lg group-hover:bg-primary group-hover:text-accent transition-all">
                    <svg class="w-6 h-6 text-primary group-hover:text-accent" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                        <path fill-rule="evenodd"
                            d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-lg text-gray-800 mb-1">Gestionar Matrículas</p>
                    <p class="text-sm text-gray-600">Matricular estudiantes en cursos</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.periodos') }}"
            class="bg-white rounded-md shadow-lg p-6 border-2 border-gray-400 hover:shadow-2xl transition-all group">
            <div class="flex items-start gap-4">
                <div class="p-3 rounded-lg group-hover:bg-primary group-hover:text-accent transition-all">
                    <svg class="w-6 h-6 text-primary group-hover:text-accent" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-lg text-gray-800 mb-1">Periodos Académicos</p>
                    <p class="text-sm text-gray-600">Configurar periodos académicos</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.calificaciones') }}"
            class="bg-white rounded-md shadow-lg p-6 border-2 border-gray-400 hover:shadow-2xl transition-all group">
            <div class="flex items-start gap-4">
                <div class="p-3 rounded-lg group-hover:bg-primary group-hover:text-accent transition-all">
                    <svg class="w-6 h-6 text-primary group-hover:text-accent" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-lg text-gray-800 mb-1">Calificaciones</p>
                    <p class="text-sm text-gray-600">Consultar calificaciones globales</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.auditorias') }}"
            class="bg-white rounded-md shadow-lg p-6 border-2 border-gray-400 hover:shadow-2xl transition-all group">
            <div class="flex items-start gap-4">
                <div class="p-3 rounded-lg group-hover:bg-primary group-hover:text-accent transition-all">
                    <svg class="w-6 h-6 text-primary group-hover:text-accent" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-lg text-gray-800 mb-1">Auditoría</p>
                    <p class="text-sm text-gray-600">Monitorear actividad del sistema</p>
                </div>
            </div>
        </a>
    </div>
@endsection
