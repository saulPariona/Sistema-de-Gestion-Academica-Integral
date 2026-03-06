<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DocenteApiController;
use App\Http\Controllers\Api\EstudianteApiController;
use Illuminate\Support\Facades\Route;

// ─── Autenticación ───────────────────────────────────────
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // ─── Estudiante ──────────────────────────────────────
    Route::prefix('estudiante')->group(function () {
        Route::get('/cursos', [EstudianteApiController::class, 'cursos']);
        Route::get('/cursos/{curso}/examenes', [EstudianteApiController::class, 'examenes']);
        Route::get('/calificaciones', [EstudianteApiController::class, 'calificaciones']);
    });

    // ─── Docente ─────────────────────────────────────────
    Route::prefix('docente')->group(function () {
        Route::get('/cursos', [DocenteApiController::class, 'cursos']);
        Route::get('/cursos/{curso}/examenes', [DocenteApiController::class, 'examenes']);
        Route::get('/cursos/{curso}/examenes/{examen}/resultados', [DocenteApiController::class, 'resultados']);
    });
});
