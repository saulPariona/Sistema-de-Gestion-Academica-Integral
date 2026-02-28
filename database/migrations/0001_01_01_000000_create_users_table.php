<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nombres', 50);
            $table->string('apellidos', 50);
            $table->string('dni', 8)->unique();
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('sexo', ['M', 'F'])->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('telefono', 15)->nullable();
            $table->string('direccion', 200)->nullable();
            $table->string('foto_perfil', 100)->nullable();
            $table->string('especialidad', 100)->nullable();
            $table->string('grado_academico', 100)->nullable();
            $table->string('cargo', 50)->nullable();
            $table->enum('rol', ['administrador', 'docente', 'estudiante'])->default('estudiante');
            $table->enum('estado', ['activo', 'inactivo', 'bloqueado'])->default('activo');
            $table->unsignedTinyInteger('intentos_fallidos')->default(0);
            $table->timestamp('bloqueado_hasta')->nullable();
            $table->timestamp('ultimo_acceso')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
