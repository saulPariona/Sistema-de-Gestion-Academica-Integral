<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('respuestas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intento_id')->constrained('intentos')->cascadeOnDelete();
            $table->foreignId('pregunta_id')->constrained('preguntas')->cascadeOnDelete();
            $table->foreignId('alternativa_id')->nullable()->constrained('alternativas')->nullOnDelete();
            $table->timestamps();
            $table->unique(['intento_id', 'pregunta_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respuestas');
    }
};
