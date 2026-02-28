<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preguntas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->foreignId('docente_id')->constrained('users')->cascadeOnDelete();
            $table->text('texto')->nullable();
            $table->string('imagen', 200)->nullable();
            $table->enum('dificultad', ['facil', 'medio', 'dificil'])->default('medio');
            $table->decimal('puntaje', 5, 2)->default(1.00);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('examen_pregunta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('examen_id')->constrained('examenes')->cascadeOnDelete();
            $table->foreignId('pregunta_id')->constrained('preguntas')->cascadeOnDelete();
            $table->unsignedInteger('orden')->nullable();
            $table->unique(['examen_id', 'pregunta_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examen_pregunta');
        Schema::dropIfExists('preguntas');
    }
};
