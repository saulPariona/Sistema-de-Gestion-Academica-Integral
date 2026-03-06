<?php

namespace Database\Seeders;

use App\Models\Examen;
use App\Models\Intento;
use App\Models\Respuesta;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IntentoRespuestaSeeder extends Seeder
{
    use WithoutModelEvents;
    public function run(): void
    {
        $this->generarIntentosExamenesCerrados();
        $this->generarIntentosExamenesPublicados();
    }

    private function generarIntentosExamenesCerrados(): void
    {
        $examenes = Examen::where('estado', 'cerrado')
            ->with(['preguntas.alternativas', 'curso.matriculas'])
            ->get();

        $this->command->info("  → Generando intentos para {$examenes->count()} exámenes cerrados...");

        foreach ($examenes as $examen) {
            $estudiantesMatriculados = $examen->curso->matriculas
                ->where('estado', 'activa')
                ->pluck('estudiante_id')
                ->toArray();

            if (empty($estudiantesMatriculados)) continue;

            $preguntas = $examen->preguntas;
            if ($preguntas->isEmpty()) continue;

            // Preparar mapa de alternativas por pregunta (evita lazy loading en bucle)
            $preguntasData = [];
            foreach ($preguntas as $pregunta) {
                $correcta = $pregunta->alternativas->firstWhere('es_correcta', true);
                $incorrectas = $pregunta->alternativas->where('es_correcta', false)->values();
                if (!$correcta || $incorrectas->isEmpty()) continue;

                $preguntasData[] = [
                    'id' => $pregunta->id,
                    'puntaje' => (float) $pregunta->puntaje,
                    'correcta_id' => $correcta->id,
                    'incorrectas_ids' => $incorrectas->pluck('id')->toArray(),
                ];
            }

            if (empty($preguntasData)) continue;

            // ~80% de matriculados rinden el examen
            $cantRinden = (int) ceil(count($estudiantesMatriculados) * 0.80);
            $estudiantesQueRinden = array_slice($estudiantesMatriculados, 0, $cantRinden);

            $intentosBatch = [];
            $intentoIndex = 0;

            foreach ($estudiantesQueRinden as $idx => $estudianteId) {
                $perfil = $this->getPerfilRendimiento($idx);
                $numIntentos = min($examen->intentos_permitidos, $perfil['intentos']);

                for ($numIntento = 1; $numIntento <= $numIntentos; $numIntento++) {
                    $inicio = Carbon::parse($examen->fecha_inicio)
                        ->addMinutes($idx % 30);
                    $tiempoUsado = intval($examen->tiempo_limite * (0.4 + ($idx % 7) * 0.1));
                    $fin = (clone $inicio)->addMinutes($tiempoUsado);

                    // ~3% abandonan
                    $estadoIntento = ($idx % 33 === 32) ? 'abandonado' : 'finalizado';

                    $intentosBatch[] = [
                        'examen_id' => $examen->id,
                        'estudiante_id' => $estudianteId,
                        'numero_intento' => $numIntento,
                        'inicio' => $inicio,
                        'fin' => $estadoIntento !== 'abandonado' ? $fin : null,
                        'puntaje_obtenido' => null,
                        'estado' => $estadoIntento,
                        'created_at' => $inicio,
                        'updated_at' => $fin ?? $inicio,
                        '_perfil_idx' => $idx,
                        '_perfil' => $perfil,
                    ];
                }
            }

            // Insertar intentos en lotes y luego crear respuestas
            $perfilesTemp = [];
            foreach ($intentosBatch as $ib) {
                $perfilesTemp[] = ['perfil_idx' => $ib['_perfil_idx'], 'perfil' => $ib['_perfil'], 'estado' => $ib['estado']];
            }

            // Limpiar campos temporales antes del insert
            $intentosClean = array_map(function ($ib) {
                unset($ib['_perfil_idx'], $ib['_perfil']);
                return $ib;
            }, $intentosBatch);

            // Insertar en lotes de 500
            $chunks = array_chunk($intentosClean, 500);
            $perfilChunks = array_chunk($perfilesTemp, 500);
            $insertedCount = 0;

            foreach ($chunks as $chunkIdx => $chunk) {
                Intento::insert($chunk);

                // Obtener los IDs recién insertados
                $primerIntento = Intento::where('examen_id', $examen->id)
                    ->orderBy('id')
                    ->skip($insertedCount)
                    ->take(count($chunk))
                    ->get();

                $respuestasBatch = [];
                foreach ($primerIntento as $localIdx => $intento) {
                    $perfilData = $perfilChunks[$chunkIdx][$localIdx] ?? null;
                    if (!$perfilData) continue;

                    $probabilidad = $perfilData['perfil']['probabilidad_acierto'];
                    $esAbandonado = $perfilData['estado'] === 'abandonado';
                    $puntajeTotal = 0.0;

                    foreach ($preguntasData as $pIdx => $pData) {
                        // Abandonados dejan ~40% sin responder
                        if ($esAbandonado && ($pIdx + $perfilData['perfil_idx']) % 3 === 0) {
                            $respuestasBatch[] = [
                                'intento_id' => $intento->id,
                                'pregunta_id' => $pData['id'],
                                'alternativa_id' => null,
                                'created_at' => $intento->inicio,
                                'updated_at' => $intento->inicio,
                            ];
                            continue;
                        }

                        // Acierto determinista basado en índice para reproducibilidad
                        $acierta = (($perfilData['perfil_idx'] * 7 + $pIdx * 13) % 100) < $probabilidad;

                        if ($acierta) {
                            $altId = $pData['correcta_id'];
                            $puntajeTotal += $pData['puntaje'];
                        } else {
                            $incIdx = ($perfilData['perfil_idx'] + $pIdx) % count($pData['incorrectas_ids']);
                            $altId = $pData['incorrectas_ids'][$incIdx];
                        }

                        $respuestasBatch[] = [
                            'intento_id' => $intento->id,
                            'pregunta_id' => $pData['id'],
                            'alternativa_id' => $altId,
                            'created_at' => $intento->inicio,
                            'updated_at' => $intento->fin ?? $intento->inicio,
                        ];
                    }

                    // Actualizar puntaje si finalizado
                    if ($intento->estado === 'finalizado') {
                        $intento->update(['puntaje_obtenido' => $puntajeTotal]);
                    }

                    // Insertar respuestas en sub-lotes
                    if (count($respuestasBatch) >= 1000) {
                        Respuesta::insert($respuestasBatch);
                        $respuestasBatch = [];
                    }
                }

                if (!empty($respuestasBatch)) {
                    Respuesta::insert($respuestasBatch);
                }

                $insertedCount += count($chunk);
            }
        }
    }

    private function generarIntentosExamenesPublicados(): void
    {
        $examenes = Examen::where('estado', 'publicado')
            ->with(['preguntas.alternativas', 'curso.matriculas'])
            ->get();

        $this->command->info("  → Generando intentos en progreso para {$examenes->count()} exámenes publicados...");

        foreach ($examenes as $examen) {
            $estudiantesMatriculados = $examen->curso->matriculas
                ->where('estado', 'activa')
                ->pluck('estudiante_id')
                ->values()
                ->toArray();

            $preguntas = $examen->preguntas;
            if ($preguntas->isEmpty() || empty($estudiantesMatriculados)) continue;

            // ~10% con intento en progreso (mín 3, máx 50)
            $cantEnProgreso = max(3, min(50, (int) ceil(count($estudiantesMatriculados) * 0.10)));
            $estudiantesEnProgreso = array_slice($estudiantesMatriculados, 0, $cantEnProgreso);

            foreach ($estudiantesEnProgreso as $idx => $estudianteId) {
                $inicio = Carbon::parse($examen->fecha_inicio)->addMinutes($idx * 2);

                $intento = Intento::create([
                    'examen_id' => $examen->id,
                    'estudiante_id' => $estudianteId,
                    'numero_intento' => 1,
                    'inicio' => $inicio,
                    'fin' => null,
                    'puntaje_obtenido' => null,
                    'estado' => 'en_progreso',
                ]);

                // Responder parcialmente
                $cantResponder = max(1, (int) ($preguntas->count() * (0.3 + ($idx % 5) * 0.15)));
                $preguntasRespondidas = $preguntas->take($cantResponder);
                $respBatch = [];

                foreach ($preguntasRespondidas as $pregunta) {
                    $alternativas = $pregunta->alternativas;
                    if ($alternativas->isEmpty()) continue;
                    $altIds = $alternativas->pluck('id')->toArray();

                    $respBatch[] = [
                        'intento_id' => $intento->id,
                        'pregunta_id' => $pregunta->id,
                        'alternativa_id' => $altIds[$idx % count($altIds)],
                        'created_at' => $inicio,
                        'updated_at' => $inicio,
                    ];
                }

                if (!empty($respBatch)) {
                    Respuesta::insert($respBatch);
                }
            }
        }
    }

    /**
     * 10 perfiles de rendimiento que simulan una distribución gaussiana.
     */
    private function getPerfilRendimiento(int $index): array
    {
        $perfiles = [
            ['probabilidad_acierto' => 92, 'intentos' => 1],  // Sobresaliente
            ['probabilidad_acierto' => 85, 'intentos' => 1],  // Muy bueno
            ['probabilidad_acierto' => 78, 'intentos' => 2],  // Bueno alto
            ['probabilidad_acierto' => 70, 'intentos' => 1],  // Bueno
            ['probabilidad_acierto' => 60, 'intentos' => 2],  // Regular alto
            ['probabilidad_acierto' => 50, 'intentos' => 2],  // Regular
            ['probabilidad_acierto' => 42, 'intentos' => 2],  // Regular bajo
            ['probabilidad_acierto' => 35, 'intentos' => 1],  // Bajo
            ['probabilidad_acierto' => 25, 'intentos' => 2],  // Deficiente
            ['probabilidad_acierto' => 65, 'intentos' => 1],  // Promedio
        ];

        return $perfiles[$index % count($perfiles)];
    }
}
