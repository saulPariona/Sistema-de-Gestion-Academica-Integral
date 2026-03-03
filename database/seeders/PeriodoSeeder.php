<?php

namespace Database\Seeders;

use App\Models\Periodo;
use Illuminate\Database\Seeder;

class PeriodoSeeder extends Seeder
{
    public function run(): void
    {
        Periodo::create([
            'nombre' => '2025-II',
            'fecha_inicio' => '2025-08-01',
            'fecha_fin' => '2025-12-20',
            'estado' => 'inactivo',
        ]);

        Periodo::create([
            'nombre' => '2026-I',
            'fecha_inicio' => '2026-03-01',
            'fecha_fin' => '2026-07-31',
            'estado' => 'activo',
        ]);

        Periodo::create([
            'nombre' => 'Ciclo Intensivo 2026',
            'fecha_inicio' => '2026-01-06',
            'fecha_fin' => '2026-02-28',
            'estado' => 'inactivo',
        ]);
    }
}
