<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Docente\DocenteController;
use App\Http\Controllers\Estudiante\EstudianteController;

Route::get('/', [LoginController::class, 'showLogin'])->name('login');
Route::post('/', [LoginController::class, 'login']);
Route::get('/forgot-password', [LoginController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [LoginController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [LoginController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('password.update');

Route::middleware(['auth', 'registrar.acceso'])->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/change-password', [LoginController::class, 'showChangePassword'])->name('password.change');
    Route::post('/change-password', [LoginController::class, 'changePassword']);

    Route::middleware('role:administrador')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
        Route::get('/usuarios/crear', [AdminController::class, 'crearUsuario'])->name('admin.usuarios.crear');
        Route::post('/usuarios', [AdminController::class, 'guardarUsuario'])->name('admin.usuarios.guardar');
        Route::get('/usuarios/{usuario}/editar', [AdminController::class, 'editarUsuario'])->name('admin.usuarios.editar');
        Route::put('/usuarios/{usuario}', [AdminController::class, 'actualizarUsuario'])->name('admin.usuarios.actualizar');
        Route::patch('/usuarios/{usuario}/toggle', [AdminController::class, 'toggleEstadoUsuario'])->name('admin.usuarios.toggle');
        Route::patch('/usuarios/{usuario}/reset-password', [AdminController::class, 'resetPasswordUsuario'])->name('admin.usuarios.reset-password');

        Route::get('/periodos', [AdminController::class, 'periodos'])->name('admin.periodos');
        Route::get('/periodos/crear', [AdminController::class, 'crearPeriodo'])->name('admin.periodos.crear');
        Route::post('/periodos', [AdminController::class, 'guardarPeriodo'])->name('admin.periodos.guardar');
        Route::get('/periodos/{periodo}/editar', [AdminController::class, 'editarPeriodo'])->name('admin.periodos.editar');
        Route::put('/periodos/{periodo}', [AdminController::class, 'actualizarPeriodo'])->name('admin.periodos.actualizar');

        Route::get('/cursos', [AdminController::class, 'cursos'])->name('admin.cursos');
        Route::get('/cursos/crear', [AdminController::class, 'crearCurso'])->name('admin.cursos.crear');
        Route::post('/cursos', [AdminController::class, 'guardarCurso'])->name('admin.cursos.guardar');
        Route::get('/cursos/{curso}/editar', [AdminController::class, 'editarCurso'])->name('admin.cursos.editar');
        Route::put('/cursos/{curso}', [AdminController::class, 'actualizarCurso'])->name('admin.cursos.actualizar');
        Route::get('/cursos/{curso}/asignar-docente', [AdminController::class, 'asignarDocente'])->name('admin.cursos.asignar-docente');
        Route::post('/cursos/{curso}/asignar-docente', [AdminController::class, 'guardarAsignacionDocente']);

        Route::get('/matriculas', [AdminController::class, 'matriculas'])->name('admin.matriculas');
        Route::get('/matriculas/crear', [AdminController::class, 'crearMatricula'])->name('admin.matriculas.crear');
        Route::post('/matriculas', [AdminController::class, 'guardarMatricula'])->name('admin.matriculas.guardar');

        Route::get('/estudiantes/{estudiante}/apoderados', [AdminController::class, 'apoderados'])->name('admin.apoderados');
        Route::get('/estudiantes/{estudiante}/apoderados/crear', [AdminController::class, 'crearApoderado'])->name('admin.apoderados.crear');
        Route::post('/apoderados', [AdminController::class, 'guardarApoderado'])->name('admin.apoderados.guardar');

        Route::get('/calificaciones', [AdminController::class, 'calificaciones'])->name('admin.calificaciones');
        Route::get('/auditorias', [AdminController::class, 'auditorias'])->name('admin.auditorias');
    });

    Route::middleware('role:docente')->prefix('docente')->group(function () {
        Route::get('/dashboard', [DocenteController::class, 'dashboard'])->name('docente.dashboard');
        Route::get('/curso/{curso}', [DocenteController::class, 'curso'])->name('docente.curso');
        Route::get('/curso/{curso}/estudiantes', [DocenteController::class, 'estudiantesCurso'])->name('docente.estudiantes');

        Route::get('/curso/{curso}/banco-preguntas', [DocenteController::class, 'bancoPreguntas'])->name('docente.banco-preguntas');
        Route::get('/curso/{curso}/preguntas/crear', [DocenteController::class, 'crearPregunta'])->name('docente.preguntas.crear');
        Route::post('/curso/{curso}/preguntas', [DocenteController::class, 'guardarPregunta'])->name('docente.preguntas.guardar');
        Route::get('/curso/{curso}/preguntas/{pregunta}/editar', [DocenteController::class, 'editarPregunta'])->name('docente.preguntas.editar');
        Route::put('/curso/{curso}/preguntas/{pregunta}', [DocenteController::class, 'actualizarPregunta'])->name('docente.preguntas.actualizar');
        Route::delete('/curso/{curso}/preguntas/{pregunta}', [DocenteController::class, 'eliminarPregunta'])->name('docente.preguntas.eliminar');

        Route::get('/curso/{curso}/examenes', [DocenteController::class, 'examenes'])->name('docente.examenes');
        Route::get('/curso/{curso}/examenes/crear', [DocenteController::class, 'crearExamen'])->name('docente.examenes.crear');
        Route::post('/curso/{curso}/examenes', [DocenteController::class, 'guardarExamen'])->name('docente.examenes.guardar');
        Route::get('/curso/{curso}/examenes/{examen}/editar', [DocenteController::class, 'editarExamen'])->name('docente.examenes.editar');
        Route::put('/curso/{curso}/examenes/{examen}', [DocenteController::class, 'actualizarExamen'])->name('docente.examenes.actualizar');
        Route::get('/curso/{curso}/examenes/{examen}/asignar-preguntas', [DocenteController::class, 'asignarPreguntas'])->name('docente.examenes.asignar-preguntas');
        Route::post('/curso/{curso}/examenes/{examen}/asignar-preguntas', [DocenteController::class, 'guardarAsignacionPreguntas']);
        Route::patch('/curso/{curso}/examenes/{examen}/publicar', [DocenteController::class, 'publicarExamen'])->name('docente.examenes.publicar');
        Route::patch('/curso/{curso}/examenes/{examen}/cerrar', [DocenteController::class, 'cerrarExamen'])->name('docente.examenes.cerrar');
        Route::get('/curso/{curso}/examenes/{examen}/resultados', [DocenteController::class, 'resultadosExamen'])->name('docente.examenes.resultados');
        Route::get('/curso/{curso}/examenes/{examen}/resultados/{intento}', [DocenteController::class, 'resultadoEstudiante'])->name('docente.examenes.resultado-estudiante');

        Route::get('/curso/{curso}/observaciones', [DocenteController::class, 'observaciones'])->name('docente.observaciones');
        Route::get('/curso/{curso}/observaciones/crear', [DocenteController::class, 'crearObservacion'])->name('docente.observaciones.crear');
        Route::post('/curso/{curso}/observaciones', [DocenteController::class, 'guardarObservacion'])->name('docente.observaciones.guardar');

        Route::get('/curso/{curso}/exportar-notas', [DocenteController::class, 'exportarNotas'])->name('docente.exportar-notas');
    });

    Route::middleware('role:estudiante')->prefix('estudiante')->group(function () {
        Route::get('/dashboard', [EstudianteController::class, 'dashboard'])->name('estudiante.dashboard');
        Route::get('/curso/{curso}', [EstudianteController::class, 'curso'])->name('estudiante.curso');
        Route::get('/curso/{curso}/examenes', [EstudianteController::class, 'examenesDisponibles'])->name('estudiante.examenes');
        Route::post('/curso/{curso}/examenes/{examen}/iniciar', [EstudianteController::class, 'iniciarExamen'])->name('estudiante.iniciar-examen');
        Route::get('/curso/{curso}/examenes/{examen}/rendir/{intento}', [EstudianteController::class, 'rendirExamen'])->name('estudiante.rendir-examen');
        Route::post('/curso/{curso}/examenes/{examen}/respuesta/{intento}', [EstudianteController::class, 'guardarRespuesta'])->name('estudiante.guardar-respuesta');
        Route::post('/curso/{curso}/examenes/{examen}/finalizar/{intento}', [EstudianteController::class, 'finalizarExamen'])->name('estudiante.finalizar-examen');
        Route::get('/curso/{curso}/examenes/{examen}/resultado/{intento}', [EstudianteController::class, 'resultadoExamen'])->name('estudiante.resultado-examen');
        Route::get('/calificaciones', [EstudianteController::class, 'calificaciones'])->name('estudiante.calificaciones');
        Route::get('/perfil', [EstudianteController::class, 'perfil'])->name('estudiante.perfil');
        Route::put('/perfil', [EstudianteController::class, 'actualizarPerfil'])->name('estudiante.perfil.actualizar');
    });
});
