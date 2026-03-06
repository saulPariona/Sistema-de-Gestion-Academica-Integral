<?php

namespace Database\Seeders;

use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Periodo;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CursoMatriculaSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $periodoActivo = Periodo::where('nombre', '2026-I')->first();
        $periodoAnterior = Periodo::where('nombre', '2025-II')->first();
        $periodoIntensivo = Periodo::where('nombre', 'Ciclo Intensivo 2026')->first();

        // Docentes principales (primeros 8 con especialidades únicas)
        $docentesPrincipales = User::where('rol', 'docente')
            ->where('estado', 'activo')
            ->whereIn('dni', ['12345678', '23456789', '34567890', '45678901', '56789012', '67890123', '78901234', '89012345'])
            ->get();

        // Docentes adicionales activos
        $docentesAdicionales = User::where('rol', 'docente')
            ->where('estado', 'activo')
            ->whereNotIn('id', $docentesPrincipales->pluck('id'))
            ->get();

        $estudiantesActivos = User::where('rol', 'estudiante')
            ->where('estado', 'activo')
            ->pluck('id')
            ->toArray();

        $this->command->info("  → " . count($estudiantesActivos) . " estudiantes activos para matricular");

        // === CURSOS PERÍODO ACTIVO (2026-I) ===
        $this->crearCursosPeriodoActivo($periodoActivo, $docentesPrincipales, $docentesAdicionales);

        // === CURSOS PERÍODO ANTERIOR (2025-II) ===
        $this->crearCursosPeriodoAnterior($periodoAnterior, $docentesPrincipales);

        // === CURSOS CICLO INTENSIVO ===
        $this->crearCursosCicloIntensivo($periodoIntensivo, $docentesPrincipales);

        // === MATRÍCULAS MASIVAS ===
        $this->matricularPeriodoActivo($periodoActivo, $estudiantesActivos);
        $this->matricularPeriodoAnterior($periodoAnterior, $estudiantesActivos);
        $this->matricularCicloIntensivo($periodoIntensivo, $estudiantesActivos);
    }

    private function crearCursosPeriodoActivo(Periodo $periodo, $docentesPrincipales, $docentesAdicionales): void
    {
        $cursosData = [
            'Álgebra'                 => 'Ecuaciones, inecuaciones, funciones y polinomios para admisión universitaria',
            'Aritmética'              => 'Números, divisibilidad, MCM-MCD, fracciones, porcentajes y promedios',
            'Geometría'               => 'Geometría plana, triángulos, cuadriláteros, circunferencia y áreas',
            'Trigonometría'           => 'Razones trigonométricas, identidades, ecuaciones y resolución de triángulos',
            'Física'                  => 'Mecánica, termodinámica, ondas, electricidad y magnetismo',
            'Química'                 => 'Estructura atómica, tabla periódica, enlace químico, estequiometría',
            'Razonamiento Matemático' => 'Lógica, operadores, sucesiones, conteo, certeza y ordenamiento',
            'Razonamiento Verbal'     => 'Comprensión lectora, analogías, sinónimos, antónimos y oraciones eliminadas',
        ];

        foreach ($docentesPrincipales as $docente) {
            if (isset($cursosData[$docente->especialidad])) {
                $curso = Curso::create([
                    'nombre' => $docente->especialidad,
                    'descripcion' => $cursosData[$docente->especialidad],
                    'periodo_id' => $periodo->id,
                ]);
                $curso->docentes()->attach($docente->id);

                // Asignar 1-2 docentes adicionales de la misma especialidad como co-docentes
                $coDocentes = $docentesAdicionales
                    ->where('especialidad', $docente->especialidad)
                    ->take(2);
                foreach ($coDocentes as $coDocente) {
                    $curso->docentes()->attach($coDocente->id);
                }
            }
        }
    }

    private function crearCursosPeriodoAnterior(Periodo $periodo, $docentesPrincipales): void
    {
        $cursosAnteriores = [
            'Álgebra'              => 'Ecuaciones, inecuaciones, funciones y polinomios para admisión universitaria',
            'Aritmética'           => 'Números, divisibilidad, MCM-MCD, fracciones, porcentajes y promedios',
            'Física'               => 'Mecánica, termodinámica, ondas, electricidad y magnetismo',
            'Razonamiento Verbal'  => 'Comprensión lectora, analogías, sinónimos, antónimos y oraciones eliminadas',
        ];

        foreach ($cursosAnteriores as $nombre => $desc) {
            $docente = $docentesPrincipales->firstWhere('especialidad', $nombre);
            if ($docente) {
                $curso = Curso::create([
                    'nombre' => $nombre,
                    'descripcion' => $desc,
                    'periodo_id' => $periodo->id,
                ]);
                $curso->docentes()->attach($docente->id);
            }
        }
    }

    private function crearCursosCicloIntensivo(Periodo $periodo, $docentesPrincipales): void
    {
        $cursosIntensivos = [
            'Álgebra'    => 'Repaso intensivo de álgebra para refuerzo vacacional',
            'Geometría'  => 'Repaso intensivo de geometría para refuerzo vacacional',
            'Física'     => 'Repaso intensivo de física para refuerzo vacacional',
        ];

        foreach ($cursosIntensivos as $nombre => $desc) {
            $docente = $docentesPrincipales->firstWhere('especialidad', $nombre);
            if ($docente) {
                $curso = Curso::create([
                    'nombre' => $nombre,
                    'descripcion' => $desc,
                    'periodo_id' => $periodo->id,
                ]);
                $curso->docentes()->attach($docente->id);
            }
        }
    }

    /**
     * Todos los estudiantes activos → 8 cursos del período activo.
     * Genera ~970 × 8 = ~7760 matrículas.
     */
    private function matricularPeriodoActivo(Periodo $periodo, array $estudiantesIds): void
    {
        $cursos = Curso::where('periodo_id', $periodo->id)->pluck('id')->toArray();
        $this->command->info("  → Matriculando " . count($estudiantesIds) . " estudiantes en " . count($cursos) . " cursos (período activo)...");

        $batch = [];
        $now = now();

        foreach ($estudiantesIds as $estId) {
            foreach ($cursos as $cursoId) {
                $batch[] = [
                    'estudiante_id' => $estId,
                    'curso_id' => $cursoId,
                    'periodo_id' => $periodo->id,
                    'estado' => 'activa',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                if (count($batch) >= 500) {
                    Matricula::insert($batch);
                    $batch = [];
                }
            }
        }

        if (!empty($batch)) {
            Matricula::insert($batch);
        }
    }

    /**
     * Primeros 200 estudiantes → 4 cursos del período anterior.
     * ~20 retirados para variedad.
     */
    private function matricularPeriodoAnterior(Periodo $periodo, array $estudiantesIds): void
    {
        $cursos = Curso::where('periodo_id', $periodo->id)->pluck('id')->toArray();
        $estudiantesAnt = array_slice($estudiantesIds, 0, 200);

        $this->command->info("  → Matriculando " . count($estudiantesAnt) . " estudiantes en " . count($cursos) . " cursos (período anterior)...");

        $batch = [];
        $now = now();

        foreach ($estudiantesAnt as $idx => $estId) {
            foreach ($cursos as $cursoId) {
                $batch[] = [
                    'estudiante_id' => $estId,
                    'curso_id' => $cursoId,
                    'periodo_id' => $periodo->id,
                    'estado' => $idx >= 180 ? 'retirada' : 'activa',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                if (count($batch) >= 500) {
                    Matricula::insert($batch);
                    $batch = [];
                }
            }
        }

        if (!empty($batch)) {
            Matricula::insert($batch);
        }
    }

    /**
     * Primeros 100 estudiantes → 3 cursos del ciclo intensivo.
     */
    private function matricularCicloIntensivo(Periodo $periodo, array $estudiantesIds): void
    {
        $cursos = Curso::where('periodo_id', $periodo->id)->pluck('id')->toArray();
        $estudiantesInt = array_slice($estudiantesIds, 0, 100);

        $this->command->info("  → Matriculando " . count($estudiantesInt) . " estudiantes en " . count($cursos) . " cursos (ciclo intensivo)...");

        $batch = [];
        $now = now();

        foreach ($estudiantesInt as $estId) {
            foreach ($cursos as $cursoId) {
                $batch[] = [
                    'estudiante_id' => $estId,
                    'curso_id' => $cursoId,
                    'periodo_id' => $periodo->id,
                    'estado' => 'activa',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                if (count($batch) >= 500) {
                    Matricula::insert($batch);
                    $batch = [];
                }
            }
        }

        if (!empty($batch)) {
            Matricula::insert($batch);
        }
    }
}
