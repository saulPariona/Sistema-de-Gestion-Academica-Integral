<?php

namespace Database\Seeders;

use App\Models\Examen;
use App\Models\Intento;
use App\Models\Respuesta;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class IntentoRespuestaSeeder extends Seeder
{
    public function run(): void
    {
        // Solo crear intentos para exámenes cerrados (ya finalizados)
        $examenesCerrados = Examen::where('estado', 'cerrado')
            ->with(['preguntas.alternativas', 'curso.matriculas'])
            ->get();

        $estudiantes = User::where('rol', 'estudiante')->where('estado', 'activo')->get();

        foreach ($examenesCerrados as $examen) {
            // Obtener estudiantes matriculados en el curso de este examen
            $estudiantesMatriculados = $examen->curso->matriculas
                ->where('estado', 'activa')
                ->pluck('estudiante_id');

            $estudiantesDelExamen = $estudiantes->whereIn('id', $estudiantesMatriculados);
            $preguntas = $examen->preguntas;

            if ($preguntas->isEmpty()) continue;

            foreach ($estudiantesDelExamen as $idx => $estudiante) {
                // Simular diferentes perfiles de rendimiento
                $perfil = $this->getPerfilRendimiento($idx);
                $numIntentos = min($examen->intentos_permitidos, $perfil['intentos']);

                for ($numIntento = 1; $numIntento <= $numIntentos; $numIntento++) {
                    $inicio = Carbon::parse($examen->fecha_inicio)
                        ->addMinutes(rand(0, 30));
                    $tiempoUsado = rand(
                        intval($examen->tiempo_limite * 0.4),
                        $examen->tiempo_limite
                    );
                    $fin = (clone $inicio)->addMinutes($tiempoUsado);

                    // Estado del intento
                    $estadoIntento = 'finalizado';
                    if ($idx % 20 === 19) {
                        $estadoIntento = 'abandonado';  // ~5% abandonan
                    }

                    $intento = Intento::create([
                        'examen_id' => $examen->id,
                        'estudiante_id' => $estudiante->id,
                        'numero_intento' => $numIntento,
                        'inicio' => $inicio,
                        'fin' => $estadoIntento !== 'abandonado' ? $fin : null,
                        'puntaje_obtenido' => null, // Se calculará
                        'estado' => $estadoIntento,
                    ]);

                    $puntajeTotal = 0;

                    foreach ($preguntas as $pregunta) {
                        $alternativas = $pregunta->alternativas;
                        if ($alternativas->isEmpty()) continue;

                        $alternativaCorrecta = $alternativas->firstWhere('es_correcta', true);
                        $alternativasIncorrectas = $alternativas->where('es_correcta', false);

                        // Decidir si responde correctamente según su perfil
                        $acierta = rand(1, 100) <= $perfil['probabilidad_acierto'];

                        // En intentos abandonados, algunas preguntas quedan sin respuesta
                        if ($estadoIntento === 'abandonado' && rand(1, 100) > 60) {
                            Respuesta::create([
                                'intento_id' => $intento->id,
                                'pregunta_id' => $pregunta->id,
                                'alternativa_id' => null,
                            ]);
                            continue;
                        }

                        if ($acierta && $alternativaCorrecta) {
                            $alternativaElegida = $alternativaCorrecta;
                            $puntajeTotal += (float)$pregunta->puntaje;
                        } else {
                            $alternativaElegida = $alternativasIncorrectas->random();
                        }

                        Respuesta::create([
                            'intento_id' => $intento->id,
                            'pregunta_id' => $pregunta->id,
                            'alternativa_id' => $alternativaElegida->id,
                        ]);
                    }

                    // Actualizar puntaje del intento
                    if ($estadoIntento === 'finalizado') {
                        $intento->update(['puntaje_obtenido' => $puntajeTotal]);
                    }
                }
            }
        }

        // Crear algunos intentos "en_progreso" para exámenes publicados
        $examenesPublicados = Examen::where('estado', 'publicado')
            ->with(['preguntas.alternativas', 'curso.matriculas'])
            ->get();

        foreach ($examenesPublicados as $examen) {
            $estudiantesMatriculados = $examen->curso->matriculas
                ->where('estado', 'activa')
                ->pluck('estudiante_id');

            // Solo 5 estudiantes con intentos en progreso por examen publicado
            $algunosEstudiantes = $estudiantes->whereIn('id', $estudiantesMatriculados)->take(5);
            $preguntas = $examen->preguntas;

            if ($preguntas->isEmpty()) continue;

            foreach ($algunosEstudiantes as $idx => $estudiante) {
                $intento = Intento::create([
                    'examen_id' => $examen->id,
                    'estudiante_id' => $estudiante->id,
                    'numero_intento' => 1,
                    'inicio' => Carbon::parse($examen->fecha_inicio)->addMinutes(rand(0, 60)),
                    'fin' => null,
                    'puntaje_obtenido' => null,
                    'estado' => 'en_progreso',
                ]);

                // Responder solo algunas preguntas (parcialmente)
                $preguntasRespondidas = $preguntas->take(rand(1, $preguntas->count() - 1));
                foreach ($preguntasRespondidas as $pregunta) {
                    $alternativas = $pregunta->alternativas;
                    if ($alternativas->isEmpty()) continue;

                    Respuesta::create([
                        'intento_id' => $intento->id,
                        'pregunta_id' => $pregunta->id,
                        'alternativa_id' => $alternativas->random()->id,
                    ]);
                }
            }
        }
    }

    /**
     * Retorna un perfil de rendimiento simulado para generar variedad en las notas.
     */
    private function getPerfilRendimiento(int $index): array
    {
        $perfiles = [
            ['probabilidad_acierto' => 90, 'intentos' => 1],  // Sobresaliente
            ['probabilidad_acierto' => 85, 'intentos' => 1],  // Muy bueno
            ['probabilidad_acierto' => 75, 'intentos' => 2],  // Bueno
            ['probabilidad_acierto' => 65, 'intentos' => 2],  // Regular alto
            ['probabilidad_acierto' => 55, 'intentos' => 2],  // Regular
            ['probabilidad_acierto' => 45, 'intentos' => 2],  // Regular bajo
            ['probabilidad_acierto' => 35, 'intentos' => 1],  // Bajo
            ['probabilidad_acierto' => 25, 'intentos' => 2],  // Deficiente
            ['probabilidad_acierto' => 70, 'intentos' => 1],  // Promedio alto
            ['probabilidad_acierto' => 50, 'intentos' => 1],  // Promedio
        ];

        return $perfiles[$index % count($perfiles)];
    }
}
