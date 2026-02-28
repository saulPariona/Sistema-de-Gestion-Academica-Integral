<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('observaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('docente_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('estudiante_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->text('texto');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('observaciones');
    }
};
