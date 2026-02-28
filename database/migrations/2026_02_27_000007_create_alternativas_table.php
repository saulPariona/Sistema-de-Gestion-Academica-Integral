<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alternativas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pregunta_id')->constrained('preguntas')->cascadeOnDelete();
            $table->text('texto')->nullable();
            $table->string('imagen', 200)->nullable();
            $table->boolean('es_correcta')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alternativas');
    }
};
