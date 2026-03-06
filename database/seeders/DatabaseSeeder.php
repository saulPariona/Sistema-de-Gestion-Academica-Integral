<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ApoderadoSeeder::class,
            PeriodoSeeder::class,
            CursoMatriculaSeeder::class,
            ExamenPreguntaSeeder::class,
            IntentoRespuestaSeeder::class,
            AuditoriaObservacionSeeder::class,
        ]);
    }
}
