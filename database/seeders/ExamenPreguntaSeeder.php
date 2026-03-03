<?php

namespace Database\Seeders;

use App\Models\Alternativa;
use App\Models\Curso;
use App\Models\Examen;
use App\Models\Periodo;
use App\Models\Pregunta;
use Illuminate\Database\Seeder;

class ExamenPreguntaSeeder extends Seeder
{
    public function run(): void
    {
        $periodoActivo = Periodo::where('nombre', '2026-I')->first();
        $cursos = Curso::where('periodo_id', $periodoActivo->id)->with('docentes')->get();

        // Banco de preguntas por materia
        $bancoPreguntas = $this->getBancoPreguntas();

        // Crear preguntas y alternativas por cada curso
        $preguntasPorCurso = [];
        foreach ($cursos as $curso) {
            $docente = $curso->docentes->first();
            if (!$docente) continue;

            $preguntas = $bancoPreguntas[$curso->nombre] ?? [];
            $preguntasPorCurso[$curso->id] = [];

            foreach ($preguntas as $pData) {
                $pregunta = Pregunta::create([
                    'curso_id' => $curso->id,
                    'docente_id' => $docente->id,
                    'texto' => $pData['texto'],
                    'dificultad' => $pData['dificultad'],
                    'puntaje' => $pData['puntaje'],
                ]);

                foreach ($pData['alternativas'] as $alt) {
                    Alternativa::create([
                        'pregunta_id' => $pregunta->id,
                        'texto' => $alt['texto'],
                        'es_correcta' => $alt['correcta'],
                    ]);
                }

                $preguntasPorCurso[$curso->id][] = $pregunta;
            }
        }

        // === CREAR EXÁMENES ===
        $examenes = $this->getExamenesConfig();

        foreach ($cursos as $curso) {
            $docente = $curso->docentes->first();
            if (!$docente || empty($preguntasPorCurso[$curso->id])) continue;

            $preguntas = $preguntasPorCurso[$curso->id];
            $totalPreguntas = count($preguntas);

            foreach ($examenes as $idx => $exConfig) {
                // Seleccionar un subconjunto de preguntas para este examen
                $numPreguntas = min($exConfig['num_preguntas'], $totalPreguntas);
                $offset = ($idx * 3) % $totalPreguntas;
                $preguntasExamen = array_slice($preguntas, $offset, $numPreguntas);

                // Si no hay suficientes, tomar desde el inicio
                if (count($preguntasExamen) < $numPreguntas) {
                    $faltan = $numPreguntas - count($preguntasExamen);
                    $preguntasExamen = array_merge($preguntasExamen, array_slice($preguntas, 0, $faltan));
                }

                $puntajeTotal = array_sum(array_map(fn($p) => (float)$p->puntaje, $preguntasExamen));

                $examen = Examen::create([
                    'curso_id' => $curso->id,
                    'docente_id' => $docente->id,
                    'titulo' => $exConfig['titulo'] . ' - ' . $curso->nombre,
                    'descripcion' => $exConfig['descripcion'],
                    'puntaje_total' => $puntajeTotal,
                    'tiempo_limite' => $exConfig['tiempo'],
                    'fecha_inicio' => $exConfig['fecha_inicio'],
                    'fecha_fin' => $exConfig['fecha_fin'],
                    'intentos_permitidos' => $exConfig['intentos'],
                    'orden_aleatorio_preguntas' => true,
                    'orden_aleatorio_alternativas' => true,
                    'mostrar_resultados' => $exConfig['mostrar_resultados'],
                    'permitir_revision' => $exConfig['permitir_revision'],
                    'navegacion_libre' => true,
                    'estado' => $exConfig['estado'],
                ]);

                // Adjuntar preguntas al examen
                $attachData = [];
                foreach ($preguntasExamen as $orden => $pregunta) {
                    $attachData[$pregunta->id] = ['orden' => $orden + 1];
                }
                $examen->preguntas()->attach($attachData);
            }
        }
    }

    private function getExamenesConfig(): array
    {
        return [
            [
                'titulo' => 'Examen Semanal S-01',
                'descripcion' => 'Evaluación semanal correspondiente a la primera semana del ciclo.',
                'tiempo' => 45,
                'fecha_inicio' => '2026-03-07 08:00:00',
                'fecha_fin' => '2026-03-07 23:59:00',
                'intentos' => 2,
                'estado' => 'cerrado',
                'num_preguntas' => 5,
                'mostrar_resultados' => true,
                'permitir_revision' => true,
            ],
            [
                'titulo' => 'Examen Semanal S-02',
                'descripcion' => 'Evaluación semanal correspondiente a la segunda semana del ciclo.',
                'tiempo' => 45,
                'fecha_inicio' => '2026-03-14 08:00:00',
                'fecha_fin' => '2026-03-14 23:59:00',
                'intentos' => 2,
                'estado' => 'cerrado',
                'num_preguntas' => 5,
                'mostrar_resultados' => true,
                'permitir_revision' => true,
            ],
            [
                'titulo' => 'Examen Semanal S-03',
                'descripcion' => 'Evaluación semanal correspondiente a la tercera semana.',
                'tiempo' => 45,
                'fecha_inicio' => '2026-03-21 08:00:00',
                'fecha_fin' => '2026-03-21 23:59:00',
                'intentos' => 2,
                'estado' => 'publicado',
                'num_preguntas' => 5,
                'mostrar_resultados' => true,
                'permitir_revision' => false,
            ],
            [
                'titulo' => 'Mensual Académico M-01',
                'descripcion' => 'Evaluación mensual que abarca los contenidos del primer mes de clases.',
                'tiempo' => 90,
                'fecha_inicio' => '2026-03-28 08:00:00',
                'fecha_fin' => '2026-03-29 23:59:00',
                'intentos' => 1,
                'estado' => 'publicado',
                'num_preguntas' => 8,
                'mostrar_resultados' => true,
                'permitir_revision' => true,
            ],
            [
                'titulo' => 'Simulacro Presencial SIM-01',
                'descripcion' => 'Simulacro tipo examen de admisión UNMSM. Condiciones reales.',
                'tiempo' => 120,
                'fecha_inicio' => '2026-04-05 09:00:00',
                'fecha_fin' => '2026-04-05 12:00:00',
                'intentos' => 1,
                'estado' => 'creado',
                'num_preguntas' => 10,
                'mostrar_resultados' => false,
                'permitir_revision' => false,
            ],
        ];
    }

    private function getBancoPreguntas(): array
    {
        return [
            // ==================== ÁLGEBRA ====================
            'Álgebra' => [
                [
                    'texto' => 'Resuelve la ecuación: 3x + 7 = 22. ¿Cuál es el valor de x?',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => 'x = 3', 'correcta' => false],
                        ['texto' => 'x = 5', 'correcta' => true],
                        ['texto' => 'x = 7', 'correcta' => false],
                        ['texto' => 'x = 4', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Si f(x) = 2x² - 3x + 1, halla f(2).',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => '3', 'correcta' => true],
                        ['texto' => '5', 'correcta' => false],
                        ['texto' => '7', 'correcta' => false],
                        ['texto' => '1', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Factoriza: x² - 9',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => '(x + 3)(x - 3)', 'correcta' => true],
                        ['texto' => '(x + 9)(x - 1)', 'correcta' => false],
                        ['texto' => '(x - 3)²', 'correcta' => false],
                        ['texto' => '(x + 3)²', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Simplifica: (a³b²)/(a²b) · (ab)/(a²b³)',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => '1/b²', 'correcta' => true],
                        ['texto' => 'a/b', 'correcta' => false],
                        ['texto' => 'ab', 'correcta' => false],
                        ['texto' => 'a²/b²', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Resuelve el sistema: 2x + y = 7; x - y = 2. Halla x + y.',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => '5', 'correcta' => false],
                        ['texto' => '4', 'correcta' => true],
                        ['texto' => '6', 'correcta' => false],
                        ['texto' => '3', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Si log₂(x) = 5, ¿cuánto vale x?',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => '10', 'correcta' => false],
                        ['texto' => '25', 'correcta' => false],
                        ['texto' => '32', 'correcta' => true],
                        ['texto' => '64', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Halla el conjunto solución de: |2x - 1| < 5',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => '⟨-2; 3⟩', 'correcta' => true],
                        ['texto' => '⟨-3; 2⟩', 'correcta' => false],
                        ['texto' => '[-2; 3]', 'correcta' => false],
                        ['texto' => '⟨-1; 3⟩', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Si P(x) = x³ - 6x² + 11x - 6, halla la suma de sus raíces.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '6', 'correcta' => true],
                        ['texto' => '-6', 'correcta' => false],
                        ['texto' => '11', 'correcta' => false],
                        ['texto' => '3', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Determina el resto de dividir P(x) = x⁴ + 2x³ - x + 3 entre (x - 1).',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '3', 'correcta' => false],
                        ['texto' => '5', 'correcta' => true],
                        ['texto' => '7', 'correcta' => false],
                        ['texto' => '1', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Resuelve: x⁴ - 5x² + 4 = 0. ¿Cuántas soluciones reales tiene?',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '2', 'correcta' => false],
                        ['texto' => '3', 'correcta' => false],
                        ['texto' => '4', 'correcta' => true],
                        ['texto' => '1', 'correcta' => false],
                    ],
                ],
            ],

            // ==================== ARITMÉTICA ====================
            'Aritmética' => [
                [
                    'texto' => 'Halla el MCD de 36 y 48.',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => '6', 'correcta' => false],
                        ['texto' => '12', 'correcta' => true],
                        ['texto' => '24', 'correcta' => false],
                        ['texto' => '8', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Halla el MCM de 12, 18 y 24.',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => '36', 'correcta' => false],
                        ['texto' => '72', 'correcta' => true],
                        ['texto' => '48', 'correcta' => false],
                        ['texto' => '144', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => '¿Cuántos divisores tiene el número 60?',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => '10', 'correcta' => false],
                        ['texto' => '12', 'correcta' => true],
                        ['texto' => '8', 'correcta' => false],
                        ['texto' => '6', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Si el 30% de un número es 45, ¿cuál es el número?',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => '135', 'correcta' => false],
                        ['texto' => '150', 'correcta' => true],
                        ['texto' => '120', 'correcta' => false],
                        ['texto' => '180', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Al simplificar la fracción 84/126, se obtiene:',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => '2/3', 'correcta' => true],
                        ['texto' => '4/6', 'correcta' => false],
                        ['texto' => '7/9', 'correcta' => false],
                        ['texto' => '14/21', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Un artículo costaba S/. 80 y aumentó en 25%. ¿Cuál es su nuevo precio?',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => 'S/. 90', 'correcta' => false],
                        ['texto' => 'S/. 100', 'correcta' => true],
                        ['texto' => 'S/. 105', 'correcta' => false],
                        ['texto' => 'S/. 95', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Convierte 0.1̄6̄ (periódico) a fracción.',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => '1/6', 'correcta' => true],
                        ['texto' => '16/99', 'correcta' => false],
                        ['texto' => '1/9', 'correcta' => false],
                        ['texto' => '4/25', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'La media aritmética de 3 números es 20. Si dos de ellos son 15 y 22, ¿cuál es el tercero?',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => '25', 'correcta' => false],
                        ['texto' => '23', 'correcta' => true],
                        ['texto' => '18', 'correcta' => false],
                        ['texto' => '20', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Halla el residuo de dividir 2²⁰ entre 7.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '1', 'correcta' => false],
                        ['texto' => '2', 'correcta' => false],
                        ['texto' => '4', 'correcta' => true],
                        ['texto' => '6', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => '¿Cuántos números primos hay entre 50 y 100?',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '8', 'correcta' => false],
                        ['texto' => '10', 'correcta' => true],
                        ['texto' => '12', 'correcta' => false],
                        ['texto' => '9', 'correcta' => false],
                    ],
                ],
            ],

            // ==================== GEOMETRÍA ====================
            'Geometría' => [
                [
                    'texto' => 'En un triángulo, los ángulos miden 40°, 60° y x°. Halla x.',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => '80°', 'correcta' => true],
                        ['texto' => '90°', 'correcta' => false],
                        ['texto' => '70°', 'correcta' => false],
                        ['texto' => '100°', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => '¿Cuál es el área de un triángulo de base 10 cm y altura 6 cm?',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => '60 cm²', 'correcta' => false],
                        ['texto' => '30 cm²', 'correcta' => true],
                        ['texto' => '16 cm²', 'correcta' => false],
                        ['texto' => '36 cm²', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'En un cuadrado de lado 8 cm, ¿cuánto mide su diagonal?',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => '8√2 cm', 'correcta' => true],
                        ['texto' => '16 cm', 'correcta' => false],
                        ['texto' => '8 cm', 'correcta' => false],
                        ['texto' => '4√2 cm', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Halla el perímetro de un rombo cuyas diagonales miden 6 cm y 8 cm.',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => '20 cm', 'correcta' => true],
                        ['texto' => '24 cm', 'correcta' => false],
                        ['texto' => '28 cm', 'correcta' => false],
                        ['texto' => '14 cm', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'En una circunferencia de radio 7 cm, ¿cuánto mide el arco que subtiende un ángulo central de 90°?',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => '7π/2 cm', 'correcta' => true],
                        ['texto' => '7π cm', 'correcta' => false],
                        ['texto' => '14π cm', 'correcta' => false],
                        ['texto' => '49π/4 cm', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Dos lados de un triángulo miden 5 y 12. Si el ángulo entre ellos es 90°, ¿cuánto mide el tercer lado?',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => '13', 'correcta' => true],
                        ['texto' => '17', 'correcta' => false],
                        ['texto' => '15', 'correcta' => false],
                        ['texto' => '10', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Halla el área de un hexágono regular de lado 6 cm.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '54√3 cm²', 'correcta' => true],
                        ['texto' => '36√3 cm²', 'correcta' => false],
                        ['texto' => '108 cm²', 'correcta' => false],
                        ['texto' => '72√3 cm²', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'En un triángulo equilátero de lado "a", halla el radio de la circunferencia inscrita.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => 'a√3/6', 'correcta' => true],
                        ['texto' => 'a√3/3', 'correcta' => false],
                        ['texto' => 'a/2', 'correcta' => false],
                        ['texto' => 'a√3/2', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Un trapecio tiene bases de 8 cm y 12 cm, y su altura es 5 cm. Halla su área.',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => '50 cm²', 'correcta' => true],
                        ['texto' => '60 cm²', 'correcta' => false],
                        ['texto' => '40 cm²', 'correcta' => false],
                        ['texto' => '100 cm²', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'La mediana relativa a la hipotenusa de un triángulo rectángulo con catetos 6 y 8 mide:',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '5', 'correcta' => true],
                        ['texto' => '7', 'correcta' => false],
                        ['texto' => '4', 'correcta' => false],
                        ['texto' => '6', 'correcta' => false],
                    ],
                ],
            ],

            // ==================== TRIGONOMETRÍA ====================
            'Trigonometría' => [
                [
                    'texto' => '¿Cuánto vale sen(30°)?',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => '1/2', 'correcta' => true],
                        ['texto' => '√2/2', 'correcta' => false],
                        ['texto' => '√3/2', 'correcta' => false],
                        ['texto' => '1', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => '¿Cuánto vale cos(60°)?',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => '1/2', 'correcta' => true],
                        ['texto' => '√3/2', 'correcta' => false],
                        ['texto' => '0', 'correcta' => false],
                        ['texto' => '1', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Convierte 3π/4 radianes a grados.',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => '135°', 'correcta' => true],
                        ['texto' => '120°', 'correcta' => false],
                        ['texto' => '150°', 'correcta' => false],
                        ['texto' => '270°', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Si tan(α) = 3/4 y α está en el primer cuadrante, halla sen(α).',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => '3/5', 'correcta' => true],
                        ['texto' => '4/5', 'correcta' => false],
                        ['texto' => '3/4', 'correcta' => false],
                        ['texto' => '5/3', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Simplifica: sen²(x) + cos²(x)',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => '1', 'correcta' => true],
                        ['texto' => '0', 'correcta' => false],
                        ['texto' => '2', 'correcta' => false],
                        ['texto' => 'sen(2x)', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Halla el valor de: sen(75°)',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => '(√6 + √2)/4', 'correcta' => true],
                        ['texto' => '(√6 - √2)/4', 'correcta' => false],
                        ['texto' => '√3/2', 'correcta' => false],
                        ['texto' => '(√3 + 1)/2', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Si sen(x) = 0.6, halla cos(2x).',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => '0.28', 'correcta' => true],
                        ['texto' => '0.36', 'correcta' => false],
                        ['texto' => '-0.28', 'correcta' => false],
                        ['texto' => '0.64', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Resuelve: 2sen(x) - 1 = 0, para x ∈ [0°, 360°⟩. ¿Cuántas soluciones hay?',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => '2', 'correcta' => true],
                        ['texto' => '1', 'correcta' => false],
                        ['texto' => '3', 'correcta' => false],
                        ['texto' => '4', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'En un triángulo ABC, a = 7, b = 8, C = 60°. Halla c usando la ley de cosenos.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '√57', 'correcta' => true],
                        ['texto' => '√113', 'correcta' => false],
                        ['texto' => '√85', 'correcta' => false],
                        ['texto' => '√49', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Reduce: (1 - cos²x)(1 + cot²x)',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '1', 'correcta' => true],
                        ['texto' => 'sen²x', 'correcta' => false],
                        ['texto' => 'cos²x', 'correcta' => false],
                        ['texto' => 'tan²x', 'correcta' => false],
                    ],
                ],
            ],

            // ==================== FÍSICA ====================
            'Física' => [
                [
                    'texto' => 'Un auto viaja a 72 km/h. ¿Cuánto es en m/s?',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => '20 m/s', 'correcta' => true],
                        ['texto' => '25 m/s', 'correcta' => false],
                        ['texto' => '36 m/s', 'correcta' => false],
                        ['texto' => '15 m/s', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => '¿Cuál es la unidad de fuerza en el Sistema Internacional?',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => 'Newton (N)', 'correcta' => true],
                        ['texto' => 'Joule (J)', 'correcta' => false],
                        ['texto' => 'Pascal (Pa)', 'correcta' => false],
                        ['texto' => 'Watt (W)', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Un cuerpo cae libremente desde 45 m de altura. ¿Cuánto tarda en llegar al suelo? (g = 10 m/s²)',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => '3 s', 'correcta' => true],
                        ['texto' => '4 s', 'correcta' => false],
                        ['texto' => '2 s', 'correcta' => false],
                        ['texto' => '5 s', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Una fuerza de 20 N actúa sobre un bloque de 4 kg en una superficie sin fricción. Halla la aceleración.',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => '5 m/s²', 'correcta' => true],
                        ['texto' => '80 m/s²', 'correcta' => false],
                        ['texto' => '4 m/s²', 'correcta' => false],
                        ['texto' => '10 m/s²', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Un proyectil se lanza con v₀ = 40 m/s y ángulo de 30°. Halla el alcance máximo. (g = 10 m/s²)',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => '80√3 m', 'correcta' => true],
                        ['texto' => '160 m', 'correcta' => false],
                        ['texto' => '80 m', 'correcta' => false],
                        ['texto' => '40√3 m', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => '¿Cuánto trabajo realiza una fuerza de 50 N al desplazar un objeto 4 m en su misma dirección?',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => '200 J', 'correcta' => true],
                        ['texto' => '12.5 J', 'correcta' => false],
                        ['texto' => '100 J', 'correcta' => false],
                        ['texto' => '54 J', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Un gas ideal a 27°C tiene un volumen de 3 L. Si la temperatura sube a 127°C a presión constante, ¿cuál será el nuevo volumen?',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => '4 L', 'correcta' => true],
                        ['texto' => '3.5 L', 'correcta' => false],
                        ['texto' => '6 L', 'correcta' => false],
                        ['texto' => '12.7 L', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Dos cargas de +3 μC y -3 μC están separadas 30 cm. Halla la fuerza entre ellas. (k = 9×10⁹ N·m²/C²)',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '0.9 N', 'correcta' => true],
                        ['texto' => '9 N', 'correcta' => false],
                        ['texto' => '0.09 N', 'correcta' => false],
                        ['texto' => '90 N', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Un bloque de 2 kg se mueve a 3 m/s. ¿Cuál es su energía cinética?',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => '9 J', 'correcta' => true],
                        ['texto' => '6 J', 'correcta' => false],
                        ['texto' => '18 J', 'correcta' => false],
                        ['texto' => '3 J', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Un circuito tiene una resistencia de 10 Ω conectada a una fuente de 20 V. Halla la corriente.',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => '2 A', 'correcta' => true],
                        ['texto' => '200 A', 'correcta' => false],
                        ['texto' => '0.5 A', 'correcta' => false],
                        ['texto' => '10 A', 'correcta' => false],
                    ],
                ],
            ],

            // ==================== QUÍMICA ====================
            'Química' => [
                [
                    'texto' => '¿Cuántos protones tiene el átomo de oxígeno (Z = 8)?',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => '8', 'correcta' => true],
                        ['texto' => '16', 'correcta' => false],
                        ['texto' => '6', 'correcta' => false],
                        ['texto' => '10', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => '¿Qué tipo de enlace se forma entre Na y Cl?',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => 'Iónico', 'correcta' => true],
                        ['texto' => 'Covalente polar', 'correcta' => false],
                        ['texto' => 'Covalente apolar', 'correcta' => false],
                        ['texto' => 'Metálico', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'La configuración electrónica del Fe (Z = 26) es:',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => '[Ar] 3d⁶ 4s²', 'correcta' => true],
                        ['texto' => '[Ar] 3d⁸', 'correcta' => false],
                        ['texto' => '[Ar] 4s² 3d⁶', 'correcta' => false],
                        ['texto' => '[Kr] 3d⁶', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Balancea: _Fe + _O₂ → _Fe₂O₃. ¿Cuál es el coeficiente del Fe?',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => '4', 'correcta' => true],
                        ['texto' => '2', 'correcta' => false],
                        ['texto' => '3', 'correcta' => false],
                        ['texto' => '1', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => '¿Cuántos moles hay en 44 g de CO₂? (C=12, O=16)',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => '1 mol', 'correcta' => true],
                        ['texto' => '2 moles', 'correcta' => false],
                        ['texto' => '0.5 moles', 'correcta' => false],
                        ['texto' => '44 moles', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => '¿Cuál es el pH de una solución con [H⁺] = 10⁻³ M?',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => '3', 'correcta' => true],
                        ['texto' => '11', 'correcta' => false],
                        ['texto' => '7', 'correcta' => false],
                        ['texto' => '-3', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'En la reacción 2H₂ + O₂ → 2H₂O, ¿cuántos litros de O₂ se necesitan para reaccionar con 10 L de H₂ en CNTP?',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => '5 L', 'correcta' => true],
                        ['texto' => '10 L', 'correcta' => false],
                        ['texto' => '20 L', 'correcta' => false],
                        ['texto' => '2.5 L', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => '¿Cuál es el nombre IUPAC del CH₃-CH₂-CH₂-OH?',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => '1-propanol', 'correcta' => true],
                        ['texto' => '2-propanol', 'correcta' => false],
                        ['texto' => 'Propanona', 'correcta' => false],
                        ['texto' => 'Ácido propanoico', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'La geometría molecular del CH₄ es:',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => 'Tetraédrica', 'correcta' => true],
                        ['texto' => 'Trigonal plana', 'correcta' => false],
                        ['texto' => 'Lineal', 'correcta' => false],
                        ['texto' => 'Piramidal', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => '¿Cuál es el número de oxidación del Mn en KMnO₄?',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '+7', 'correcta' => true],
                        ['texto' => '+5', 'correcta' => false],
                        ['texto' => '+4', 'correcta' => false],
                        ['texto' => '+6', 'correcta' => false],
                    ],
                ],
            ],

            // ==================== RAZONAMIENTO MATEMÁTICO ====================
            'Razonamiento Matemático' => [
                [
                    'texto' => '¿Qué número continúa la sucesión: 2, 6, 12, 20, 30, ...?',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => '42', 'correcta' => true],
                        ['texto' => '40', 'correcta' => false],
                        ['texto' => '36', 'correcta' => false],
                        ['texto' => '38', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Si A * B = 2A + B – 1, halla 3 * 5.',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => '10', 'correcta' => true],
                        ['texto' => '11', 'correcta' => false],
                        ['texto' => '8', 'correcta' => false],
                        ['texto' => '12', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'En una reunión hay 8 personas. Si cada una saluda a las demás con un apretón de manos, ¿cuántos saludos hay en total?',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => '28', 'correcta' => true],
                        ['texto' => '56', 'correcta' => false],
                        ['texto' => '64', 'correcta' => false],
                        ['texto' => '36', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Un reloj marca las 3:00. ¿Cuál es el ángulo entre las manecillas?',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => '90°', 'correcta' => true],
                        ['texto' => '60°', 'correcta' => false],
                        ['texto' => '120°', 'correcta' => false],
                        ['texto' => '180°', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Se tienen 3 dados. ¿De cuántas formas se puede obtener una suma de 5?',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => '6', 'correcta' => true],
                        ['texto' => '3', 'correcta' => false],
                        ['texto' => '10', 'correcta' => false],
                        ['texto' => '5', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Pedro es mayor que Luis. Luis es mayor que Mario. Mario es menor que ana. ¿Quién es necesariamente el mayor?',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => 'Pedro', 'correcta' => true],
                        ['texto' => 'Ana', 'correcta' => false],
                        ['texto' => 'Luis', 'correcta' => false],
                        ['texto' => 'No se puede determinar', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => '¿Cuántos triángulos hay en la siguiente figura? (Un pentágono con todas sus diagonales)',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => '35', 'correcta' => true],
                        ['texto' => '30', 'correcta' => false],
                        ['texto' => '25', 'correcta' => false],
                        ['texto' => '40', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Al lanzar 2 dados, ¿cuál es la probabilidad de obtener suma 7?',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => '1/6', 'correcta' => true],
                        ['texto' => '1/12', 'correcta' => false],
                        ['texto' => '7/36', 'correcta' => false],
                        ['texto' => '1/7', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'En una caja hay 5 bolas rojas, 3 verdes y 2 azules. ¿Cuántas bolas como mínimo debo sacar para tener la certeza de obtener 3 del mismo color?',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '7', 'correcta' => true],
                        ['texto' => '6', 'correcta' => false],
                        ['texto' => '8', 'correcta' => false],
                        ['texto' => '5', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => '¿Cuántos números de 4 cifras diferentes se pueden formar con los dígitos 1, 2, 3, 4, 5?',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '120', 'correcta' => true],
                        ['texto' => '625', 'correcta' => false],
                        ['texto' => '60', 'correcta' => false],
                        ['texto' => '24', 'correcta' => false],
                    ],
                ],
            ],

            // ==================== RAZONAMIENTO VERBAL ====================
            'Razonamiento Verbal' => [
                [
                    'texto' => 'UBICUO es a OMNIPRESENTE como EFÍMERO es a:',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => 'Fugaz', 'correcta' => true],
                        ['texto' => 'Eterno', 'correcta' => false],
                        ['texto' => 'Estable', 'correcta' => false],
                        ['texto' => 'Perenne', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Señale el sinónimo de ACUCIOSO:',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => 'Diligente', 'correcta' => true],
                        ['texto' => 'Perezoso', 'correcta' => false],
                        ['texto' => 'Ansioso', 'correcta' => false],
                        ['texto' => 'Cauteloso', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Señale el antónimo de PROLIJO:',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => 'Conciso', 'correcta' => true],
                        ['texto' => 'Extenso', 'correcta' => false],
                        ['texto' => 'Detallado', 'correcta' => false],
                        ['texto' => 'Minucioso', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'MÉDICO es a HOSPITAL como MAESTRO es a:',
                    'dificultad' => 'facil', 'puntaje' => 2,
                    'alternativas' => [
                        ['texto' => 'Escuela', 'correcta' => true],
                        ['texto' => 'Aula', 'correcta' => false],
                        ['texto' => 'Libro', 'correcta' => false],
                        ['texto' => 'Estudiante', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Identifique la oración eliminada: (I) El agua cubre el 71% de la Tierra. (II) Los océanos contienen el 97% del agua. (III) El agua dulce está en ríos y lagos. (IV) La contaminación del aire crece cada año. (V) Los glaciares almacenan agua dulce.',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => 'IV', 'correcta' => true],
                        ['texto' => 'II', 'correcta' => false],
                        ['texto' => 'V', 'correcta' => false],
                        ['texto' => 'I', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Marque la alternativa que presenta uso correcto de la tilde:',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => 'Él dio su opinión.', 'correcta' => true],
                        ['texto' => 'El dió su opinión.', 'correcta' => false],
                        ['texto' => 'Él dió su opinión.', 'correcta' => false],
                        ['texto' => 'El dio su opinión.', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'En el enunciado "La perseverancia es la madre de la ciencia", la figura literaria empleada es:',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => 'Metáfora', 'correcta' => true],
                        ['texto' => 'Símil', 'correcta' => false],
                        ['texto' => 'Hipérbole', 'correcta' => false],
                        ['texto' => 'Metonimia', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => '¿Cuál es el tema central del siguiente texto? "Las abejas polinizan el 80% de las plantas con flores. Sin ellas, la producción de alimentos disminuiría drásticamente..."',
                    'dificultad' => 'medio', 'puntaje' => 3,
                    'alternativas' => [
                        ['texto' => 'La importancia de las abejas para la agricultura', 'correcta' => true],
                        ['texto' => 'La producción de miel', 'correcta' => false],
                        ['texto' => 'La extinción de las abejas', 'correcta' => false],
                        ['texto' => 'Las flores y su polinización', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Indique la serie de palabras correctamente escritas:',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => 'absorber, exhibir, exhortar', 'correcta' => true],
                        ['texto' => 'absorver, exibir, exhortar', 'correcta' => false],
                        ['texto' => 'absorber, exhibir, exortar', 'correcta' => false],
                        ['texto' => 'absorver, exibir, exortar', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Complete la analogía: ÍNFIMO es a MÍNIMO como INGENTE es a:',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => 'Enorme', 'correcta' => true],
                        ['texto' => 'Pequeño', 'correcta' => false],
                        ['texto' => 'Inteligente', 'correcta' => false],
                        ['texto' => 'Ingenuo', 'correcta' => false],
                    ],
                ],
            ],
        ];
    }
}
