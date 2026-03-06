<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\PeriodoController;
use App\Http\Controllers\Admin\CursoController as AdminCursoController;
use App\Http\Controllers\Admin\MatriculaController;
use App\Http\Controllers\Admin\ApoderadoController;
use App\Http\Controllers\Admin\CalificacionController;
use App\Http\Controllers\Admin\AuditoriaController;
use App\Http\Controllers\Docente\DashboardController as DocenteDashboard;
use App\Http\Controllers\Docente\PreguntaController;
use App\Http\Controllers\Docente\ExamenController;
use App\Http\Controllers\Docente\ObservacionController;
use App\Http\Controllers\Estudiante\DashboardController as EstudianteDashboard;
use App\Http\Controllers\Estudiante\ExamenController as EstudianteExamenController;
use App\Http\Controllers\Estudiante\CalificacionController as EstudianteCalificacionController;
use App\Http\Controllers\Estudiante\PerfilController;

Route::get('/', [LoginController::class, 'showLogin'])->name('login');
Route::post('/', [LoginController::class, 'login'])->middleware('throttle:login');
Route::get('/forgot-password', [LoginController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [LoginController::class, 'sendResetLink'])->name('password.email')->middleware('throttle:password-reset');
Route::get('/reset-password/{token}', [LoginController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('password.update')->middleware('throttle:password-reset');

Route::middleware(['auth', 'registrar.acceso'])->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/change-password', [LoginController::class, 'showChangePassword'])->name('password.change');
    Route::post('/change-password', [LoginController::class, 'changePassword']);

    Route::middleware('role:administrador')->prefix('admin')->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('admin.dashboard');

        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('admin.usuarios');
        Route::get('/usuarios/crear', [UsuarioController::class, 'create'])->name('admin.usuarios.crear');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('admin.usuarios.guardar');
        Route::get('/usuarios/{usuario}/editar', [UsuarioController::class, 'edit'])->name('admin.usuarios.editar');
        Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('admin.usuarios.actualizar');
        Route::patch('/usuarios/{usuario}/toggle', [UsuarioController::class, 'toggleEstado'])->name('admin.usuarios.toggle');
        Route::patch('/usuarios/{usuario}/reset-password', [UsuarioController::class, 'resetPassword'])->name('admin.usuarios.reset-password');

        Route::get('/periodos', [PeriodoController::class, 'index'])->name('admin.periodos');
        Route::get('/periodos/crear', [PeriodoController::class, 'create'])->name('admin.periodos.crear');
        Route::post('/periodos', [PeriodoController::class, 'store'])->name('admin.periodos.guardar');
        Route::get('/periodos/{periodo}/editar', [PeriodoController::class, 'edit'])->name('admin.periodos.editar');
        Route::put('/periodos/{periodo}', [PeriodoController::class, 'update'])->name('admin.periodos.actualizar');

        Route::get('/cursos', [AdminCursoController::class, 'index'])->name('admin.cursos');
        Route::get('/cursos/crear', [AdminCursoController::class, 'create'])->name('admin.cursos.crear');
        Route::post('/cursos', [AdminCursoController::class, 'store'])->name('admin.cursos.guardar');
        Route::get('/cursos/{curso}/editar', [AdminCursoController::class, 'edit'])->name('admin.cursos.editar');
        Route::put('/cursos/{curso}', [AdminCursoController::class, 'update'])->name('admin.cursos.actualizar');
        Route::get('/cursos/{curso}/asignar-docente', [AdminCursoController::class, 'asignarDocente'])->name('admin.cursos.asignar-docente');
        Route::post('/cursos/{curso}/asignar-docente', [AdminCursoController::class, 'guardarAsignacionDocente']);

        Route::get('/matriculas', [MatriculaController::class, 'index'])->name('admin.matriculas');
        Route::get('/matriculas/crear', [MatriculaController::class, 'create'])->name('admin.matriculas.crear');
        Route::post('/matriculas', [MatriculaController::class, 'store'])->name('admin.matriculas.guardar');

        Route::get('/estudiantes/{estudiante}/apoderados', [ApoderadoController::class, 'index'])->name('admin.apoderados');
        Route::get('/estudiantes/{estudiante}/apoderados/crear', [ApoderadoController::class, 'create'])->name('admin.apoderados.crear');
        Route::post('/apoderados', [ApoderadoController::class, 'store'])->name('admin.apoderados.guardar');

        Route::get('/calificaciones', [CalificacionController::class, 'index'])->name('admin.calificaciones');
        Route::get('/auditorias', [AuditoriaController::class, 'index'])->name('admin.auditorias');
    });

    Route::middleware('role:docente')->prefix('docente')->group(function () {
        Route::get('/dashboard', DocenteDashboard::class)->name('docente.dashboard');
        Route::get('/curso/{curso}', [DocenteDashboard::class, 'curso'])->name('docente.curso');
        Route::get('/curso/{curso}/estudiantes', [DocenteDashboard::class, 'estudiantesCurso'])->name('docente.estudiantes');

        Route::get('/curso/{curso}/banco-preguntas', [PreguntaController::class, 'index'])->name('docente.banco-preguntas');
        Route::get('/curso/{curso}/preguntas/crear', [PreguntaController::class, 'create'])->name('docente.preguntas.crear');
        Route::post('/curso/{curso}/preguntas', [PreguntaController::class, 'store'])->name('docente.preguntas.guardar');
        Route::get('/curso/{curso}/preguntas/{pregunta}/editar', [PreguntaController::class, 'edit'])->name('docente.preguntas.editar');
        Route::put('/curso/{curso}/preguntas/{pregunta}', [PreguntaController::class, 'update'])->name('docente.preguntas.actualizar');
        Route::delete('/curso/{curso}/preguntas/{pregunta}', [PreguntaController::class, 'destroy'])->name('docente.preguntas.eliminar');

        Route::get('/curso/{curso}/examenes', [ExamenController::class, 'index'])->name('docente.examenes');
        Route::get('/curso/{curso}/examenes/crear', [ExamenController::class, 'create'])->name('docente.examenes.crear');
        Route::post('/curso/{curso}/examenes', [ExamenController::class, 'store'])->name('docente.examenes.guardar');
        Route::get('/curso/{curso}/examenes/{examen}/editar', [ExamenController::class, 'edit'])->name('docente.examenes.editar');
        Route::put('/curso/{curso}/examenes/{examen}', [ExamenController::class, 'update'])->name('docente.examenes.actualizar');
        Route::get('/curso/{curso}/examenes/{examen}/asignar-preguntas', [ExamenController::class, 'asignarPreguntas'])->name('docente.examenes.asignar-preguntas');
        Route::post('/curso/{curso}/examenes/{examen}/asignar-preguntas', [ExamenController::class, 'guardarAsignacionPreguntas']);
        Route::patch('/curso/{curso}/examenes/{examen}/publicar', [ExamenController::class, 'publicar'])->name('docente.examenes.publicar');
        Route::patch('/curso/{curso}/examenes/{examen}/cerrar', [ExamenController::class, 'cerrar'])->name('docente.examenes.cerrar');
        Route::get('/curso/{curso}/examenes/{examen}/resultados', [ExamenController::class, 'resultados'])->name('docente.examenes.resultados');
        Route::get('/curso/{curso}/examenes/{examen}/resultados/{intento}', [ExamenController::class, 'resultadoEstudiante'])->name('docente.examenes.resultado-estudiante');

        Route::get('/curso/{curso}/observaciones', [ObservacionController::class, 'index'])->name('docente.observaciones');
        Route::get('/curso/{curso}/observaciones/crear', [ObservacionController::class, 'create'])->name('docente.observaciones.crear');
        Route::post('/curso/{curso}/observaciones', [ObservacionController::class, 'store'])->name('docente.observaciones.guardar');

        Route::get('/curso/{curso}/exportar-notas', [ObservacionController::class, 'exportarNotas'])->name('docente.exportar-notas');
    });

    Route::middleware('role:estudiante')->prefix('estudiante')->group(function () {
        Route::get('/dashboard', EstudianteDashboard::class)->name('estudiante.dashboard');
        Route::get('/curso/{curso}', [EstudianteDashboard::class, 'curso'])->name('estudiante.curso');

        Route::get('/curso/{curso}/examenes', [EstudianteExamenController::class, 'index'])->name('estudiante.examenes');
        Route::post('/curso/{curso}/examenes/{examen}/iniciar', [EstudianteExamenController::class, 'iniciar'])->name('estudiante.iniciar-examen');
        Route::get('/curso/{curso}/examenes/{examen}/rendir/{intento}', [EstudianteExamenController::class, 'rendir'])->name('estudiante.rendir-examen');
        Route::post('/curso/{curso}/examenes/{examen}/respuesta/{intento}', [EstudianteExamenController::class, 'guardarRespuesta'])->name('estudiante.guardar-respuesta');
        Route::post('/curso/{curso}/examenes/{examen}/finalizar/{intento}', [EstudianteExamenController::class, 'finalizar'])->name('estudiante.finalizar-examen');
        Route::get('/curso/{curso}/examenes/{examen}/resultado/{intento}', [EstudianteExamenController::class, 'resultado'])->name('estudiante.resultado-examen');

        Route::get('/calificaciones', EstudianteCalificacionController::class)->name('estudiante.calificaciones');

        Route::get('/perfil', [PerfilController::class, 'show'])->name('estudiante.perfil');
        Route::put('/perfil', [PerfilController::class, 'update'])->name('estudiante.perfil.actualizar');
    });
});
