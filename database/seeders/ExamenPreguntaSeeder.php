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
        $bancoUNCP = $this->getBancoPreguntasUNCP();

        // Fusionar bancos
        foreach ($bancoUNCP as $materia => $preguntas) {
            if (isset($bancoPreguntas[$materia])) {
                $bancoPreguntas[$materia] = array_merge($bancoPreguntas[$materia], $preguntas);
            } else {
                $bancoPreguntas[$materia] = $preguntas;
            }
        }

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
            [
                'titulo' => 'Simulacro UNCP Admisión SIM-02',
                'descripcion' => 'Simulacro tipo examen de admisión UNCP. Nivel difícil. Condiciones reales de examen.',
                'tiempo' => 90,
                'fecha_inicio' => '2026-04-12 08:00:00',
                'fecha_fin' => '2026-04-12 11:00:00',
                'intentos' => 1,
                'estado' => 'creado',
                'num_preguntas' => 15,
                'mostrar_resultados' => false,
                'permitir_revision' => false,
            ],
            [
                'titulo' => 'Simulacro UNCP Admisión SIM-03',
                'descripcion' => 'Segundo simulacro tipo admisión UNCP. Nivel difícil con preguntas de razonamiento avanzado.',
                'tiempo' => 90,
                'fecha_inicio' => '2026-04-19 08:00:00',
                'fecha_fin' => '2026-04-19 11:00:00',
                'intentos' => 1,
                'estado' => 'creado',
                'num_preguntas' => 15,
                'mostrar_resultados' => false,
                'permitir_revision' => false,
            ],
            [
                'titulo' => 'Simulacro UNCP Final SIM-04',
                'descripcion' => 'Simulacro final tipo admisión UNCP. Evaluación integral de todas las materias.',
                'tiempo' => 120,
                'fecha_inicio' => '2026-04-26 08:00:00',
                'fecha_fin' => '2026-04-26 12:00:00',
                'intentos' => 1,
                'estado' => 'creado',
                'num_preguntas' => 20,
                'mostrar_resultados' => true,
                'permitir_revision' => true,
            ],
        ];
    }

    private function getBancoPreguntasUNCP(): array
    {
        return [
            // ==================== ÁLGEBRA - NIVEL UNCP ====================
            'Álgebra' => [
                [
                    'texto' => 'Si x + 1/x = 3, halla el valor de x³ + 1/x³.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '18', 'correcta' => true],
                        ['texto' => '27', 'correcta' => false],
                        ['texto' => '9', 'correcta' => false],
                        ['texto' => '24', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Halla la suma de las raíces reales de la ecuación: √(x + 3) + √(x - 1) = 4.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '6', 'correcta' => false],
                        ['texto' => '1', 'correcta' => false],
                        ['texto' => '6', 'correcta' => true],
                        ['texto' => '3', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Si f(x) = x² - 2x + 3 y g(x) = 2x - 1, halla (f ∘ g)(2).',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '6', 'correcta' => true],
                        ['texto' => '8', 'correcta' => false],
                        ['texto' => '12', 'correcta' => false],
                        ['texto' => '3', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Resuelve la inecuación: (x² - 4)/(x - 3) ≤ 0. Indica el número de enteros en la solución.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '4', 'correcta' => true],
                        ['texto' => '3', 'correcta' => false],
                        ['texto' => '5', 'correcta' => false],
                        ['texto' => '6', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Si log(x) + log(x-3) = 1, halla el valor de x.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '5', 'correcta' => true],
                        ['texto' => '-2', 'correcta' => false],
                        ['texto' => '10', 'correcta' => false],
                        ['texto' => '3', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Halla el término independiente en el desarrollo del binomio (x² - 1/x)⁶.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '15', 'correcta' => true],
                        ['texto' => '-15', 'correcta' => false],
                        ['texto' => '20', 'correcta' => false],
                        ['texto' => '-20', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Si las raíces de x² + bx + c = 0 son r y s, y r² + s² = 5, rs = -2, halla b² - 2c.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '5', 'correcta' => false],
                        ['texto' => '9', 'correcta' => true],
                        ['texto' => '1', 'correcta' => false],
                        ['texto' => '7', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Determina el rango de la función f(x) = (2x + 1)/(x - 3), x ≠ 3.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => 'ℝ - {2}', 'correcta' => true],
                        ['texto' => 'ℝ - {3}', 'correcta' => false],
                        ['texto' => 'ℝ - {-1}', 'correcta' => false],
                        ['texto' => 'ℝ', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Si 2^(x+1) + 2^(x-1) = 5, halla el valor de 4^x.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '4', 'correcta' => true],
                        ['texto' => '2', 'correcta' => false],
                        ['texto' => '8', 'correcta' => false],
                        ['texto' => '16', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Halla la suma de coeficientes del polinomio P(x) = (2x - 3)⁴.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '1', 'correcta' => true],
                        ['texto' => '16', 'correcta' => false],
                        ['texto' => '81', 'correcta' => false],
                        ['texto' => '-1', 'correcta' => false],
                    ],
                ],
            ],

            // ==================== ARITMÉTICA - NIVEL UNCP ====================
            'Aritmética' => [
                [
                    'texto' => 'Halla el mayor número de 4 cifras que al dividirse entre 13, 17 y 19 deja siempre residuo 5.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '8393', 'correcta' => true],
                        ['texto' => '9999', 'correcta' => false],
                        ['texto' => '8398', 'correcta' => false],
                        ['texto' => '8388', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Si N = 2⁴ × 3² × 5, ¿cuántos divisores compuestos tiene N?',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '26', 'correcta' => true],
                        ['texto' => '30', 'correcta' => false],
                        ['texto' => '24', 'correcta' => false],
                        ['texto' => '20', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Convierte 0.2̅3̅ (periódico mixto) a fracción irreducible.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '7/30', 'correcta' => true],
                        ['texto' => '23/99', 'correcta' => false],
                        ['texto' => '23/90', 'correcta' => false],
                        ['texto' => '7/33', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'La suma de dos números es 84 y su MCD es 12. ¿Cuántos pares de números cumplen esta condición?',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '2', 'correcta' => true],
                        ['texto' => '3', 'correcta' => false],
                        ['texto' => '4', 'correcta' => false],
                        ['texto' => '1', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Halla la suma de cifras de: S = 999...9 (80 nueves) × 5.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '360', 'correcta' => true],
                        ['texto' => '400', 'correcta' => false],
                        ['texto' => '720', 'correcta' => false],
                        ['texto' => '320', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Si abc(7) = cba(9), halla a + b + c.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '12', 'correcta' => true],
                        ['texto' => '10', 'correcta' => false],
                        ['texto' => '15', 'correcta' => false],
                        ['texto' => '9', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Un comerciante gana el 30% del precio de venta. Si vendió un artículo en S/.650, ¿cuánto ganó?',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => 'S/.195', 'correcta' => true],
                        ['texto' => 'S/.150', 'correcta' => false],
                        ['texto' => 'S/.200', 'correcta' => false],
                        ['texto' => 'S/.500', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Tres obreros A, B y C pueden hacer una obra en 6, 8 y 12 días respectivamente. ¿En cuántos días harán la obra juntos?',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '8/3 días', 'correcta' => true],
                        ['texto' => '3 días', 'correcta' => false],
                        ['texto' => '4 días', 'correcta' => false],
                        ['texto' => '2 días', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Halla el residuo de dividir 3¹⁰⁰ entre 7.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '4', 'correcta' => true],
                        ['texto' => '1', 'correcta' => false],
                        ['texto' => '2', 'correcta' => false],
                        ['texto' => '6', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Si la razón aritmética de dos números es 8 y su razón geométrica es 3/5, halla el mayor de ellos.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '20', 'correcta' => true],
                        ['texto' => '12', 'correcta' => false],
                        ['texto' => '24', 'correcta' => false],
                        ['texto' => '16', 'correcta' => false],
                    ],
                ],
            ],

            // ==================== GEOMETRÍA - NIVEL UNCP ====================
            'Geometría' => [
                [
                    'texto' => 'En un triángulo ABC, se traza la ceviana BD. Si AB = 8, BC = 6, AD = 4 y DC = 3. Halla BD.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '√34', 'correcta' => true],
                        ['texto' => '6', 'correcta' => false],
                        ['texto' => '√30', 'correcta' => false],
                        ['texto' => '7', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'En un triángulo rectángulo, la hipotenusa mide 10. Las proyecciones de los catetos sobre la hipotenusa miden 4 y 6 respectivamente. Halla el cateto menor.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '2√10', 'correcta' => true],
                        ['texto' => '√40', 'correcta' => false],
                        ['texto' => '6', 'correcta' => false],
                        ['texto' => '2√15', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Una circunferencia tiene un radio de 5 cm. Desde un punto exterior a 13 cm del centro se traza una secante. Si la cuerda que determina mide 8 cm, halla la distancia del punto exterior al punto más cercano de la circunferencia.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '5 cm', 'correcta' => false],
                        ['texto' => '8 cm', 'correcta' => true],
                        ['texto' => '7 cm', 'correcta' => false],
                        ['texto' => '9 cm', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'En un cuadrilátero ABCD inscrito en una circunferencia, AB = 5, BC = 6, CD = 3, DA = 4 y AC = 7. Halla el área del cuadrilátero.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '2√455/4', 'correcta' => false],
                        ['texto' => '√455', 'correcta' => false],
                        ['texto' => '30', 'correcta' => true],
                        ['texto' => '24', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Halla el volumen de un tetraedro regular de arista 6 cm.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '18√2 cm³', 'correcta' => true],
                        ['texto' => '36√2 cm³', 'correcta' => false],
                        ['texto' => '24√3 cm³', 'correcta' => false],
                        ['texto' => '12√2 cm³', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'En un trapecio isósceles ABCD, las bases miden AB = 16 y CD = 8, y los lados no paralelos miden 5. Halla la longitud de la diagonal AC.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '√89', 'correcta' => true],
                        ['texto' => '√73', 'correcta' => false],
                        ['texto' => '10', 'correcta' => false],
                        ['texto' => '√97', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Dos circunferencias exteriores tangentes tienen radios 3 y 5. Halla la longitud de la tangente externa común.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '4√3', 'correcta' => true],
                        ['texto' => '2√15', 'correcta' => false],
                        ['texto' => '8', 'correcta' => false],
                        ['texto' => '6', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'En un triángulo ABC, se sabe que AB = 13, BC = 14, CA = 15. Halla el área del triángulo.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '84', 'correcta' => true],
                        ['texto' => '90', 'correcta' => false],
                        ['texto' => '78', 'correcta' => false],
                        ['texto' => '96', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Un cono tiene generatriz 13 cm y radio de base 5 cm. Halla el área lateral.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '65π cm²', 'correcta' => true],
                        ['texto' => '60π cm²', 'correcta' => false],
                        ['texto' => '130π cm²', 'correcta' => false],
                        ['texto' => '25π cm²', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'La mediana de un triángulo relativa al lado de 10 cm mide 8 cm. Si otro lado mide 6 cm, halla el tercer lado.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '2√46', 'correcta' => true],
                        ['texto' => '12', 'correcta' => false],
                        ['texto' => '2√38', 'correcta' => false],
                        ['texto' => '10', 'correcta' => false],
                    ],
                ],
            ],

            // ==================== TRIGONOMETRÍA - NIVEL UNCP ====================
            'Trigonometría' => [
                [
                    'texto' => 'Simplifica: (sen3x + senx) / (cos3x + cosx).',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => 'tan(2x)', 'correcta' => true],
                        ['texto' => 'tan(x)', 'correcta' => false],
                        ['texto' => '2tan(x)', 'correcta' => false],
                        ['texto' => 'cot(2x)', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Si senx + cosx = √2, halla sen(2x).',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '1', 'correcta' => true],
                        ['texto' => '√2', 'correcta' => false],
                        ['texto' => '1/2', 'correcta' => false],
                        ['texto' => '0', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Halla el valor de: cos20°·cos40°·cos80°.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '1/8', 'correcta' => true],
                        ['texto' => '1/4', 'correcta' => false],
                        ['texto' => '√3/8', 'correcta' => false],
                        ['texto' => '1/2', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Si tan(α/2) = t, expresa sen(α) en función de t.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '2t/(1 + t²)', 'correcta' => true],
                        ['texto' => '(1 - t²)/(1 + t²)', 'correcta' => false],
                        ['texto' => '2t/(1 - t²)', 'correcta' => false],
                        ['texto' => 't/(1 + t)', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Resuelve: cos(2x) = 1/2, para x ∈ [0; 2π⟩. Halla la suma de soluciones.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '4π', 'correcta' => true],
                        ['texto' => '2π', 'correcta' => false],
                        ['texto' => '3π', 'correcta' => false],
                        ['texto' => '5π', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'En un triángulo ABC: a/senA = b/senB = c/senC = 2R. Si a = 8, A = 30°, halla R.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '8', 'correcta' => true],
                        ['texto' => '4', 'correcta' => false],
                        ['texto' => '16', 'correcta' => false],
                        ['texto' => '4√2', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Reduce: sen⁴x + cos⁴x + 2sen²x·cos²x.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '1', 'correcta' => true],
                        ['texto' => '2', 'correcta' => false],
                        ['texto' => 'sen²(2x)', 'correcta' => false],
                        ['texto' => 'cos²(2x)', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Halla el período de f(x) = 3sen(2x - π/4) + 1.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => 'π', 'correcta' => true],
                        ['texto' => '2π', 'correcta' => false],
                        ['texto' => 'π/2', 'correcta' => false],
                        ['texto' => '4π', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Si arctan(x) + arctan(1/x) = π/2, (x > 0), determina el valor de x² + 1/x² sabiendo que x = 2.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '17/4', 'correcta' => true],
                        ['texto' => '5', 'correcta' => false],
                        ['texto' => '4', 'correcta' => false],
                        ['texto' => '10/4', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Calcula: E = (1 + tan²x)/(csc²x).',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => 'tan²x', 'correcta' => false],
                        ['texto' => 'sec²x', 'correcta' => false],
                        ['texto' => 'sen²x/cos²x · 1', 'correcta' => false],
                        ['texto' => 'sec²x · sen²x', 'correcta' => true],
                    ],
                ],
            ],

            // ==================== FÍSICA - NIVEL UNCP ====================
            'Física' => [
                [
                    'texto' => 'Un bloque de 5 kg está sobre un plano inclinado de 37° (μₛ = 0.5). Determina si el bloque se desliza y la fuerza de fricción. (g = 10 m/s², sen37° = 0.6, cos37° = 0.8)',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => 'Se desliza, f = 20 N', 'correcta' => false],
                        ['texto' => 'No se desliza, f = 30 N', 'correcta' => true],
                        ['texto' => 'Se desliza, f = 30 N', 'correcta' => false],
                        ['texto' => 'No se desliza, f = 20 N', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Dos bloques de 3 kg y 5 kg están unidos por una cuerda sobre una mesa sin fricción. Si se aplica F = 40 N al de 5 kg, halla la tensión en la cuerda.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '15 N', 'correcta' => true],
                        ['texto' => '25 N', 'correcta' => false],
                        ['texto' => '20 N', 'correcta' => false],
                        ['texto' => '10 N', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Un satélite orbita la Tierra a una altura h = R (R = radio terrestre). Si g₀ = 10 m/s², ¿cuál es la aceleración gravitatoria a esa altura?',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '2.5 m/s²', 'correcta' => true],
                        ['texto' => '5 m/s²', 'correcta' => false],
                        ['texto' => '10 m/s²', 'correcta' => false],
                        ['texto' => '1.25 m/s²', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Un resorte de constante k = 200 N/m se comprime 0.1 m y lanza un bloque de 0.5 kg. Halla la velocidad del bloque al separarse del resorte.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '2 m/s', 'correcta' => true],
                        ['texto' => '4 m/s', 'correcta' => false],
                        ['texto' => '1 m/s', 'correcta' => false],
                        ['texto' => '√2 m/s', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Una esfera de 2 kg se mueve a 6 m/s y choca elásticamente con otra de 4 kg en reposo. Halla la velocidad de la esfera de 2 kg después del choque.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '-2 m/s', 'correcta' => true],
                        ['texto' => '2 m/s', 'correcta' => false],
                        ['texto' => '0 m/s', 'correcta' => false],
                        ['texto' => '-4 m/s', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Un péndulo simple tiene período T = 2 s en la Tierra. ¿Cuál será su período en la Luna donde g_Luna = g/6?',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '2√6 s', 'correcta' => true],
                        ['texto' => '12 s', 'correcta' => false],
                        ['texto' => '6 s', 'correcta' => false],
                        ['texto' => '2/√6 s', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Tres resistencias de 6Ω, 3Ω y 2Ω están en paralelo. Halla la resistencia equivalente.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '1 Ω', 'correcta' => true],
                        ['texto' => '11 Ω', 'correcta' => false],
                        ['texto' => '2 Ω', 'correcta' => false],
                        ['texto' => '0.5 Ω', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Un cuerpo realiza MAS con A = 0.2 m y f = 5 Hz. Halla la velocidad máxima.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '2π m/s', 'correcta' => true],
                        ['texto' => 'π m/s', 'correcta' => false],
                        ['texto' => '10π m/s', 'correcta' => false],
                        ['texto' => '0.2π m/s', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Un capacitor de 10 μF se carga con 100 V. Halla la energía almacenada.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '0.05 J', 'correcta' => true],
                        ['texto' => '0.5 J', 'correcta' => false],
                        ['texto' => '5 J', 'correcta' => false],
                        ['texto' => '0.005 J', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Una onda sonora tiene frecuencia 680 Hz y se propaga a 340 m/s. Si se refleja en una pared, ¿cuál es la distancia entre dos nodos consecutivos de la onda estacionaria?',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '0.25 m', 'correcta' => true],
                        ['texto' => '0.5 m', 'correcta' => false],
                        ['texto' => '1 m', 'correcta' => false],
                        ['texto' => '0.125 m', 'correcta' => false],
                    ],
                ],
            ],

            // ==================== QUÍMICA - NIVEL UNCP ====================
            'Química' => [
                [
                    'texto' => 'Indica los números cuánticos del último electrón del Fe²⁺ (Z = 26).',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '(3, 2, -2, -1/2)', 'correcta' => true],
                        ['texto' => '(3, 2, 2, +1/2)', 'correcta' => false],
                        ['texto' => '(4, 0, 0, -1/2)', 'correcta' => false],
                        ['texto' => '(3, 2, 0, +1/2)', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => '¿Cuántos gramos de NaOH se necesitan para preparar 500 mL de una solución 0.5 M? (Na=23, O=16, H=1)',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '10 g', 'correcta' => true],
                        ['texto' => '20 g', 'correcta' => false],
                        ['texto' => '5 g', 'correcta' => false],
                        ['texto' => '40 g', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'En la siguiente reacción redox: MnO₄⁻ + Fe²⁺ → Mn²⁺ + Fe³⁺ (medio ácido), ¿cuántos moles de Fe²⁺ reaccionan con un mol de MnO₄⁻?',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '5', 'correcta' => true],
                        ['texto' => '3', 'correcta' => false],
                        ['texto' => '7', 'correcta' => false],
                        ['texto' => '2', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => '¿Cuál es la presión de 2 moles de un gas ideal a 27°C en un recipiente de 8.2 L? (R = 0.082 atm·L/mol·K)',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '6 atm', 'correcta' => true],
                        ['texto' => '3 atm', 'correcta' => false],
                        ['texto' => '12 atm', 'correcta' => false],
                        ['texto' => '1 atm', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Calcula el ΔH de la reacción C + O₂ → CO₂, dados: C + 1/2 O₂ → CO (ΔH₁ = -110 kJ) y CO + 1/2 O₂ → CO₂ (ΔH₂ = -283 kJ).',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '-393 kJ', 'correcta' => true],
                        ['texto' => '-173 kJ', 'correcta' => false],
                        ['texto' => '+393 kJ', 'correcta' => false],
                        ['texto' => '-566 kJ', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Si 50 mL de HCl 0.2 M se mezclan con 50 mL de NaOH 0.1 M, ¿cuál es el pH de la solución resultante? (log2 = 0.3)',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '1.3', 'correcta' => true],
                        ['texto' => '7', 'correcta' => false],
                        ['texto' => '2', 'correcta' => false],
                        ['texto' => '1', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => '¿Cuántos litros de CO₂ en CNTP se producen al reaccionar 100 g de CaCO₃ con exceso de HCl? (Ca=40, C=12, O=16)',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '22.4 L', 'correcta' => true],
                        ['texto' => '44.8 L', 'correcta' => false],
                        ['texto' => '11.2 L', 'correcta' => false],
                        ['texto' => '33.6 L', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'En una celda galvánica Zn/Zn²⁺ // Cu²⁺/Cu, calcula la fem estándar. (E°Zn = -0.76 V, E°Cu = +0.34 V)',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '1.10 V', 'correcta' => true],
                        ['texto' => '0.42 V', 'correcta' => false],
                        ['texto' => '-1.10 V', 'correcta' => false],
                        ['texto' => '0.76 V', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'El compuesto CH₃-CO-CH₃ corresponde a un(a):',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => 'Cetona (propanona)', 'correcta' => true],
                        ['texto' => 'Aldehído (propanal)', 'correcta' => false],
                        ['texto' => 'Alcohol (propanol)', 'correcta' => false],
                        ['texto' => 'Éster (propanoato)', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Ordena los siguientes elementos según su electronegatividad de mayor a menor: F, O, N, Cl.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => 'F > O > N > Cl', 'correcta' => false],
                        ['texto' => 'F > O > Cl > N', 'correcta' => true],
                        ['texto' => 'O > F > N > Cl', 'correcta' => false],
                        ['texto' => 'F > Cl > O > N', 'correcta' => false],
                    ],
                ],
            ],

            // ==================== RAZONAMIENTO MATEMÁTICO - NIVEL UNCP ====================
            'Razonamiento Matemático' => [
                [
                    'texto' => 'Si al número 5aba5 se le multiplica por 3, resulta un número de 5 cifras que empieza y termina en 7. Halla a + b.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '10', 'correcta' => false],
                        ['texto' => '11', 'correcta' => true],
                        ['texto' => '12', 'correcta' => false],
                        ['texto' => '9', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'En una fila de 100 focos apagados, se realiza lo siguiente: se encienden todos, luego se apagan los múltiplos de 2, luego se cambia el estado de los múltiplos de 3, y así hasta los múltiplos de 100. ¿Cuántos focos quedan encendidos?',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '10', 'correcta' => true],
                        ['texto' => '50', 'correcta' => false],
                        ['texto' => '25', 'correcta' => false],
                        ['texto' => '12', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'En una reunión de 12 personas, cada una le da la mano a exactamente 5 personas. ¿Cuántos apretones de manos hubo en total?',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '30', 'correcta' => true],
                        ['texto' => '60', 'correcta' => false],
                        ['texto' => '66', 'correcta' => false],
                        ['texto' => '55', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => '¿De cuántas formas se pueden sentar 5 personas alrededor de una mesa circular?',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '24', 'correcta' => true],
                        ['texto' => '120', 'correcta' => false],
                        ['texto' => '60', 'correcta' => false],
                        ['texto' => '12', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Un caracol sube por una pared de 10 m. De día sube 3 m y de noche baja 1 m. ¿En qué día llega a la cima?',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => 'Día 5', 'correcta' => true],
                        ['texto' => 'Día 4', 'correcta' => false],
                        ['texto' => 'Día 6', 'correcta' => false],
                        ['texto' => 'Día 10', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Se tienen 4 bolas rojas idénticas y 3 bolas azules idénticas. ¿De cuántas formas se pueden colocar en fila?',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '35', 'correcta' => true],
                        ['texto' => '5040', 'correcta' => false],
                        ['texto' => '210', 'correcta' => false],
                        ['texto' => '42', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Si A ∩ B tiene 5 elementos, A tiene 12 elementos, B tiene 10 elementos. ¿Cuántos elementos tiene A ∪ B?',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '17', 'correcta' => true],
                        ['texto' => '22', 'correcta' => false],
                        ['texto' => '27', 'correcta' => false],
                        ['texto' => '7', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Un tren de 200 m de largo cruza un túnel de 300 m en 25 segundos. ¿Cuál es la velocidad del tren?',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '20 m/s', 'correcta' => true],
                        ['texto' => '12 m/s', 'correcta' => false],
                        ['texto' => '8 m/s', 'correcta' => false],
                        ['texto' => '25 m/s', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'La edad de un padre es el triple de la de su hijo. Hace 10 años la edad del padre era cinco veces la del hijo. ¿Cuántos años tiene el hijo?',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '20', 'correcta' => true],
                        ['texto' => '15', 'correcta' => false],
                        ['texto' => '25', 'correcta' => false],
                        ['texto' => '30', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Ana, Beto y Carlos pesan juntos 180 kg. Ana y Beto pesan 130 kg. Beto y Carlos pesan 110 kg. ¿Cuánto pesa Beto?',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => '60 kg', 'correcta' => true],
                        ['texto' => '50 kg', 'correcta' => false],
                        ['texto' => '70 kg', 'correcta' => false],
                        ['texto' => '80 kg', 'correcta' => false],
                    ],
                ],
            ],

            // ==================== RAZONAMIENTO VERBAL - NIVEL UNCP ====================
            'Razonamiento Verbal' => [
                [
                    'texto' => 'INCÓLUME es a ILESO como INDEMNE es a:',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => 'Intacto', 'correcta' => true],
                        ['texto' => 'Vulnerable', 'correcta' => false],
                        ['texto' => 'Frágil', 'correcta' => false],
                        ['texto' => 'Incompleto', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Señale el antónimo de PUSILÁNIME:',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => 'Intrépido', 'correcta' => true],
                        ['texto' => 'Cobarde', 'correcta' => false],
                        ['texto' => 'Timorato', 'correcta' => false],
                        ['texto' => 'Medroso', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'PRÓDIGO es a GENEROSO como AVARO es a:',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => 'Tacaño', 'correcta' => true],
                        ['texto' => 'Rico', 'correcta' => false],
                        ['texto' => 'Dadivoso', 'correcta' => false],
                        ['texto' => 'Prudente', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Identifique el término excluido: OBCECADO',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => 'Pertinaz', 'correcta' => false],
                        ['texto' => 'Contumaz', 'correcta' => false],
                        ['texto' => 'Sagaz', 'correcta' => true],
                        ['texto' => 'Testarudo', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Lea el siguiente texto: "La paradoja de la tolerancia, propuesta por Karl Popper, establece que si una sociedad es ilimitadamente tolerante, su capacidad de ser tolerante es finalmente destruida por los intolerantes". ¿Cuál es la idea principal?',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => 'La tolerancia absoluta conduce a su propia destrucción', 'correcta' => true],
                        ['texto' => 'Karl Popper fue un filósofo intolerante', 'correcta' => false],
                        ['texto' => 'La sociedad debe ser siempre tolerante', 'correcta' => false],
                        ['texto' => 'Los intolerantes deben ser tolerados siempre', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Identifique la oración eliminada: (I) El ADN contiene la información genética de los seres vivos. (II) Está formado por una doble hélice de nucleótidos. (III) Watson y Crick descubrieron su estructura en 1953. (IV) La célula es la unidad básica de la vida. (V) Los genes son segmentos específicos de ADN.',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => 'IV', 'correcta' => true],
                        ['texto' => 'III', 'correcta' => false],
                        ['texto' => 'II', 'correcta' => false],
                        ['texto' => 'V', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Complete la analogía: ICONOCLASTA es a TRADICIÓN como HEREJE es a:',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => 'Dogma', 'correcta' => true],
                        ['texto' => 'Iglesia', 'correcta' => false],
                        ['texto' => 'Castigo', 'correcta' => false],
                        ['texto' => 'Creencia', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Señale el sinónimo contextual de SOSLAYAR en: "No podemos soslayar la importancia de la educación".',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => 'Eludir', 'correcta' => true],
                        ['texto' => 'Destacar', 'correcta' => false],
                        ['texto' => 'Confirmar', 'correcta' => false],
                        ['texto' => 'Resaltar', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'En el enunciado "Sus ojos eran dos luceros en la oscuridad", la figura literaria es:',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => 'Metáfora', 'correcta' => true],
                        ['texto' => 'Símil', 'correcta' => false],
                        ['texto' => 'Hipérbaton', 'correcta' => false],
                        ['texto' => 'Metonimia', 'correcta' => false],
                    ],
                ],
                [
                    'texto' => 'Marque la alternativa que presenta correcta concordancia gramatical:',
                    'dificultad' => 'dificil', 'puntaje' => 4,
                    'alternativas' => [
                        ['texto' => 'Hubieron muchos problemas', 'correcta' => false],
                        ['texto' => 'Hicieron bastante calor', 'correcta' => false],
                        ['texto' => 'Habrá graves consecuencias', 'correcta' => true],
                        ['texto' => 'Habían demasiados errores', 'correcta' => false],
                    ],
                ],
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
