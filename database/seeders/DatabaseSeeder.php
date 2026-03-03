<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Ejecuta todos los seeders en el orden correcto respetando las FK.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,            // Admins, docentes, estudiantes (52 usuarios)
            ApoderadoSeeder::class,        // 30 apoderados vinculados a estudiantes
            PeriodoSeeder::class,          // 3 periodos académicos
            CursoMatriculaSeeder::class,   // 12 cursos + matrículas masivas
            ExamenPreguntaSeeder::class,   // 80 preguntas + 320 alternativas + 40 exámenes
            IntentoRespuestaSeeder::class, // Intentos y respuestas simulados
            AuditoriaObservacionSeeder::class, // Auditorías, historial de roles, observaciones
        ]);
    }
}
