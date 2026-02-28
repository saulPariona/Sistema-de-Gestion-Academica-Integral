<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('examen_id')->constrained('examenes')->cascadeOnDelete();
            $table->foreignId('estudiante_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('numero_intento')->default(1);
            $table->dateTime('inicio');
            $table->dateTime('fin')->nullable();
            $table->decimal('puntaje_obtenido', 5, 2)->nullable();
            $table->enum('estado', ['en_progreso', 'finalizado', 'abandonado'])->default('en_progreso');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intentos');
    }
};
