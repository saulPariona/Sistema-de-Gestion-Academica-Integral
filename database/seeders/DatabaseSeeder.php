<?php

namespace Database\Seeders;

use App\Models\Alternativa;
use App\Models\Apoderado;
use App\Models\Curso;
use App\Models\Examen;
use App\Models\Periodo;
use App\Models\Pregunta;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'nombres' => 'Admin',
            'apellidos' => 'Sistema',
            'dni' => '00000001',
            'email' => 'admin@colegiomp.edu.pe',
            'password' => Hash::make('Admin1234'),
            'rol' => 'administrador',
            'estado' => 'activo',
        ]);

        $docente1 = User::create([
            'nombres' => 'Carlos',
            'apellidos' => 'García López',
            'dni' => '12345678',
            'email' => 'carlos.garcia@colegiomp.edu.pe',
            'password' => Hash::make('Docente1234'),
            'rol' => 'docente',
            'estado' => 'activo',
            'especialidad' => 'Matemáticas',
            'grado_academico' => 'Magíster en Matemáticas',
        ]);

        $docente2 = User::create([
            'nombres' => 'Ana',
            'apellidos' => 'Martínez Rojas',
            'dni' => '23456789',
            'email' => 'ana.martinez@colegiomp.edu.pe',
            'password' => Hash::make('Docente1234'),
            'rol' => 'docente',
            'estado' => 'activo',
            'especialidad' => 'Comunicación',
            'grado_academico' => 'Licenciada en Educación',
        ]);

        $estudiante1 = User::create([
            'nombres' => 'María',
            'apellidos' => 'Pérez Sánchez',
            'dni' => '87654321',
            'email' => 'maria.perez@colegiomp.edu.pe',
            'password' => Hash::make('Estudiante1234'),
            'rol' => 'estudiante',
            'estado' => 'activo',
            'fecha_nacimiento' => '2008-05-15',
            'sexo' => 'F',
        ]);

        $estudiante2 = User::create([
            'nombres' => 'Juan',
            'apellidos' => 'López Torres',
            'dni' => '98765432',
            'email' => 'juan.lopez@colegiomp.edu.pe',
            'password' => Hash::make('Estudiante1234'),
            'rol' => 'estudiante',
            'estado' => 'activo',
            'fecha_nacimiento' => '2008-08-20',
            'sexo' => 'M',
        ]);

        Apoderado::create([
            'estudiante_id' => $estudiante1->id,
            'nombre_completo' => 'Roberto Pérez González',
            'dni' => '40123456',
            'telefono' => '987654321',
            'email' => 'roberto.perez@gmail.com',
            'parentesco' => 'padre',
        ]);

        $periodo = Periodo::create([
            'nombre' => '2026-I',
            'fecha_inicio' => '2026-03-01',
            'fecha_fin' => '2026-07-31',
            'estado' => 'activo',
        ]);

        $cursoMate = Curso::create([
            'nombre' => 'Matemática Básica',
            'descripcion' => 'Curso introductorio de matemáticas para secundaria',
            'periodo_id' => $periodo->id,
        ]);

        $cursoMate->docentes()->attach($docente1->id);

        $cursoMate->matriculas()->create([
            'estudiante_id' => $estudiante1->id,
            'periodo_id' => $periodo->id,
            'estado' => 'activa',
        ]);

        $cursoMate->matriculas()->create([
            'estudiante_id' => $estudiante2->id,
            'periodo_id' => $periodo->id,
            'estado' => 'activa',
        ]);

        $cursoCom = Curso::create([
            'nombre' => 'Comunicación Integral',
            'descripcion' => 'Desarrollo de habilidades comunicativas',
            'periodo_id' => $periodo->id,
        ]);

        $cursoCom->docentes()->attach($docente2->id);

        $cursoCom->matriculas()->create([
            'estudiante_id' => $estudiante1->id,
            'periodo_id' => $periodo->id,
            'estado' => 'activa',
        ]);

        $pregunta1 = Pregunta::create([
            'curso_id' => $cursoMate->id,
            'docente_id' => $docente1->id,
            'texto' => '¿Cuánto es 2 + 2?',
            'dificultad' => 'facil',
            'puntaje' => 1,
        ]);

        Alternativa::create(['pregunta_id' => $pregunta1->id, 'texto' => '3', 'es_correcta' => false]);
        Alternativa::create(['pregunta_id' => $pregunta1->id, 'texto' => '4', 'es_correcta' => true]);
        Alternativa::create(['pregunta_id' => $pregunta1->id, 'texto' => '5', 'es_correcta' => false]);
        Alternativa::create(['pregunta_id' => $pregunta1->id, 'texto' => '6', 'es_correcta' => false]);

        $pregunta2 = Pregunta::create([
            'curso_id' => $cursoMate->id,
            'docente_id' => $docente1->id,
            'texto' => '¿Cuál es el resultado de 5 × 3?',
            'dificultad' => 'facil',
            'puntaje' => 1,
        ]);

        Alternativa::create(['pregunta_id' => $pregunta2->id, 'texto' => '8', 'es_correcta' => false]);
        Alternativa::create(['pregunta_id' => $pregunta2->id, 'texto' => '15', 'es_correcta' => true]);
        Alternativa::create(['pregunta_id' => $pregunta2->id, 'texto' => '25', 'es_correcta' => false]);
        Alternativa::create(['pregunta_id' => $pregunta2->id, 'texto' => '12', 'es_correcta' => false]);

        $examen = Examen::create([
            'curso_id' => $cursoMate->id,
            'docente_id' => $docente1->id,
            'titulo' => 'Examen de Diagnóstico',
            'descripcion' => 'Evaluación inicial de conocimientos matemáticos',
            'puntaje_total' => 20,
            'tiempo_limite' => 30,
            'fecha_inicio' => now()->subHour(),
            'fecha_fin' => now()->addDays(30),
            'intentos_permitidos' => 2,
            'orden_aleatorio_preguntas' => true,
            'orden_aleatorio_alternativas' => true,
            'mostrar_resultados' => true,
            'permitir_revision' => true,
            'navegacion_libre' => true,
            'estado' => 'publicado',
        ]);

        $examen->preguntas()->attach([
            $pregunta1->id => ['orden' => 1],
            $pregunta2->id => ['orden' => 2],
        ]);
    }
}
