<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->foreignId('periodo_id')->constrained('periodos')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('curso_docente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->foreignId('docente_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['curso_id', 'docente_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curso_docente');
        Schema::dropIfExists('cursos');
    }
};
