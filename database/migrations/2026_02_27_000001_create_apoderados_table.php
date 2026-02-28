<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apoderados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('users')->cascadeOnDelete();
            $table->string('nombre_completo', 100);
            $table->string('dni', 8);
            $table->string('telefono', 15);
            $table->string('email')->nullable();
            $table->string('parentesco', 30);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apoderados');
    }
};
