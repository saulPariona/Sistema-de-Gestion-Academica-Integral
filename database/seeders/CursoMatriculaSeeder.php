<?php

namespace Database\Seeders;

use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Periodo;
use App\Models\User;
use Illuminate\Database\Seeder;

class CursoMatriculaSeeder extends Seeder
{
    public function run(): void
    {
        $periodoActivo = Periodo::where('nombre', '2026-I')->first();
        $periodoAnterior = Periodo::where('nombre', '2025-II')->first();

        $docentes = User::where('rol', 'docente')->get();
        $estudiantes = User::where('rol', 'estudiante')->where('estado', 'activo')->get();

        // Mapeo de especialidad → nombre del curso para período activo
        $cursosData = [
            'Álgebra'                   => ['nombre' => 'Álgebra',                     'descripcion' => 'Ecuaciones, inecuaciones, funciones y polinomios para admisión universitaria'],
            'Aritmética'                => ['nombre' => 'Aritmética',                   'descripcion' => 'Números, divisibilidad, MCM-MCD, fracciones, porcentajes y promedios'],
            'Geometría'                 => ['nombre' => 'Geometría',                    'descripcion' => 'Geometría plana, triángulos, cuadriláteros, circunferencia y áreas'],
            'Trigonometría'             => ['nombre' => 'Trigonometría',                 'descripcion' => 'Razones trigonométricas, identidades, ecuaciones y resolución de triángulos'],
            'Física'                    => ['nombre' => 'Física',                       'descripcion' => 'Mecánica, termodinámica, ondas, electricidad y magnetismo'],
            'Química'                   => ['nombre' => 'Química',                      'descripcion' => 'Estructura atómica, tabla periódica, enlace químico, estequiometría'],
            'Razonamiento Matemático'   => ['nombre' => 'Razonamiento Matemático',      'descripcion' => 'Lógica, operadores, sucesiones, conteo, certeza y ordenamiento'],
            'Razonamiento Verbal'       => ['nombre' => 'Razonamiento Verbal',          'descripcion' => 'Comprensión lectora, analogías, sinónimos, antónimos y oraciones eliminadas'],
        ];

        // === CURSOS PARA PERÍODO ACTIVO (2026-I) ===
        foreach ($docentes as $docente) {
            if (isset($cursosData[$docente->especialidad])) {
                $data = $cursosData[$docente->especialidad];
                $curso = Curso::create([
                    'nombre' => $data['nombre'],
                    'descripcion' => $data['descripcion'],
                    'periodo_id' => $periodoActivo->id,
                ]);
                $curso->docentes()->attach($docente->id);
            }
        }

        // === CURSOS PARA PERÍODO ANTERIOR (2025-II) - solo 4 cursos ===
        $cursosAnteriores = ['Álgebra', 'Aritmética', 'Física', 'Razonamiento Verbal'];
        foreach ($cursosAnteriores as $nombreCurso) {
            $docente = $docentes->firstWhere('especialidad', $nombreCurso);
            if ($docente) {
                $curso = Curso::create([
                    'nombre' => $nombreCurso,
                    'descripcion' => $cursosData[$nombreCurso]['descripcion'] ?? '',
                    'periodo_id' => $periodoAnterior->id,
                ]);
                $curso->docentes()->attach($docente->id);
            }
        }

        // === MATRÍCULAS PERÍODO ACTIVO ===
        $cursosActivos = Curso::where('periodo_id', $periodoActivo->id)->get();

        // Matricular a todos los estudiantes activos en todos los cursos del período activo
        foreach ($estudiantes as $est) {
            foreach ($cursosActivos as $curso) {
                Matricula::create([
                    'estudiante_id' => $est->id,
                    'curso_id' => $curso->id,
                    'periodo_id' => $periodoActivo->id,
                    'estado' => 'activa',
                ]);
            }
        }

        // === MATRÍCULAS PERÍODO ANTERIOR (solo 20 estudiantes en esos 4 cursos) ===
        $cursosAnt = Curso::where('periodo_id', $periodoAnterior->id)->get();
        $estudiantesAnt = $estudiantes->take(20);

        foreach ($estudiantesAnt as $est) {
            foreach ($cursosAnt as $curso) {
                Matricula::create([
                    'estudiante_id' => $est->id,
                    'curso_id' => $curso->id,
                    'periodo_id' => $periodoAnterior->id,
                    'estado' => 'activa',
                ]);
            }
        }

        // 2 estudiantes retirados en período anterior
        $retirados = $estudiantes->slice(18, 2);
        foreach ($retirados as $est) {
            Matricula::where('estudiante_id', $est->id)
                ->where('periodo_id', $periodoAnterior->id)
                ->update(['estado' => 'retirada']);
        }
    }
}
