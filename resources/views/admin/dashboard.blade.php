@extends('layouts.app')
@section('contenido')
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Panel de Administración</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
            <p class="text-sm text-gray-500 uppercase">Estudiantes</p>
            <p class="text-3xl font-bold text-blue-600">{{ $totalEstudiantes }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
            <p class="text-sm text-gray-500 uppercase">Docentes</p>
            <p class="text-3xl font-bold text-green-600">{{ $totalDocentes }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
            <p class="text-sm text-gray-500 uppercase">Cursos</p>
            <p class="text-3xl font-bold text-purple-600">{{ $totalCursos }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
            <p class="text-sm text-gray-500 uppercase">Periodo Activo</p>
            <p class="text-lg font-bold text-orange-600">{{ $periodoActivo?->nombre ?? 'Ninguno' }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('admin.usuarios') }}"
            class="block bg-white border border-gray-200 rounded-lg p-5 shadow-sm hover:shadow-md hover:border-blue-300">
            <p class="font-bold text-gray-800">Gestionar Usuarios</p>
            <p class="text-sm text-gray-500">Crear, editar, activar/desactivar usuarios</p>
        </a>
        <a href="{{ route('admin.cursos') }}"
            class="block bg-white border border-gray-200 rounded-lg p-5 shadow-sm hover:shadow-md hover:border-blue-300">
            <p class="font-bold text-gray-800">Gestionar Cursos</p>
            <p class="text-sm text-gray-500">Administrar cursos y asignar docentes</p>
        </a>
        <a href="{{ route('admin.matriculas') }}"
            class="block bg-white border border-gray-200 rounded-lg p-5 shadow-sm hover:shadow-md hover:border-blue-300">
            <p class="font-bold text-gray-800">Gestionar Matrículas</p>
            <p class="text-sm text-gray-500">Matricular estudiantes en cursos</p>
        </a>
        <a href="{{ route('admin.periodos') }}"
            class="block bg-white border border-gray-200 rounded-lg p-5 shadow-sm hover:shadow-md hover:border-blue-300">
            <p class="font-bold text-gray-800">Periodos Académicos</p>
            <p class="text-sm text-gray-500">Configurar periodos académicos</p>
        </a>
        <a href="{{ route('admin.calificaciones') }}"
            class="block bg-white border border-gray-200 rounded-lg p-5 shadow-sm hover:shadow-md hover:border-blue-300">
            <p class="font-bold text-gray-800">Calificaciones</p>
            <p class="text-sm text-gray-500">Consultar calificaciones globales</p>
        </a>
        <a href="{{ route('admin.auditorias') }}"
            class="block bg-white border border-gray-200 rounded-lg p-5 shadow-sm hover:shadow-md hover:border-blue-300">
            <p class="font-bold text-gray-800">Auditoría</p>
            <p class="text-sm text-gray-500">Monitorear actividad del sistema</p>
        </a>
    </div>
@endsection
