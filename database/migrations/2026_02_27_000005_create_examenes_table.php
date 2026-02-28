<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('examenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->foreignId('docente_id')->constrained('users')->cascadeOnDelete();
            $table->string('titulo', 200);
            $table->text('descripcion')->nullable();
            $table->decimal('puntaje_total', 5, 2)->default(20.00);
            $table->unsignedInteger('tiempo_limite');
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin');
            $table->unsignedTinyInteger('intentos_permitidos')->default(1);
            $table->boolean('orden_aleatorio_preguntas')->default(true);
            $table->boolean('orden_aleatorio_alternativas')->default(true);
            $table->boolean('mostrar_resultados')->default(true);
            $table->boolean('permitir_revision')->default(false);
            $table->boolean('navegacion_libre')->default(true);
            $table->enum('estado', ['creado', 'publicado', 'cerrado'])->default('creado');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examenes');
    }
};
