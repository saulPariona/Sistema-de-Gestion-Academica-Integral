<?php

namespace Database\Seeders;

use App\Models\Auditoria;
use App\Models\HistorialRol;
use App\Models\Observacion;
use App\Models\User;
use App\Models\Curso;
use App\Models\Periodo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuditoriaObservacionSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $admin = User::where('rol', 'administrador')->first();
        $docentes = User::where('rol', 'docente')->where('estado', 'activo')->get();
        $estudiantes = User::where('rol', 'estudiante')->get();
        $periodoActivo = Periodo::where('nombre', '2026-I')->first();
        $cursos = Curso::where('periodo_id', $periodoActivo->id)->with('docentes')->get();

        $this->command->info('  → Creando auditorías, historial de roles y observaciones...');

        $this->crearAuditorias($admin, $docentes, $estudiantes, $periodoActivo);
        $this->crearHistorialRoles($admin, $docentes);
        $this->crearObservaciones($docentes, $estudiantes, $cursos);
    }

    private function crearAuditorias($admin, $docentes, $estudiantes, $periodoActivo): void
    {
        $acciones = [];

        // Auditorías de creación de usuarios (10 registros)
        for ($i = 0; $i < 10; $i++) {
            $est = $estudiantes->values()->get($i);
            if (!$est) continue;

            $acciones[] = [
                'user_id' => $admin->id,
                'accion' => 'crear_usuario',
                'modelo' => 'User',
                'modelo_id' => $est->id,
                'datos_anteriores' => null,
                'datos_nuevos' => json_encode(['nombres' => $est->nombres, 'apellidos' => $est->apellidos, 'rol' => 'estudiante', 'estado' => 'activo']),
                'ip' => '192.168.1.10',
                'created_at' => '2026-02-' . str_pad(15 + $i, 2, '0', STR_PAD_LEFT) . ' 09:' . str_pad($i * 5, 2, '0', STR_PAD_LEFT) . ':00',
                'updated_at' => '2026-02-' . str_pad(15 + $i, 2, '0', STR_PAD_LEFT) . ' 09:' . str_pad($i * 5, 2, '0', STR_PAD_LEFT) . ':00',
            ];
        }

        // Auditorías de actualización de usuarios (8 registros)
        for ($i = 0; $i < 8; $i++) {
            $est = $estudiantes->values()->get(10 + $i);
            if (!$est) continue;

            $acciones[] = [
                'user_id' => $admin->id,
                'accion' => 'actualizar_usuario',
                'modelo' => 'User',
                'modelo_id' => $est->id,
                'datos_anteriores' => json_encode(['telefono' => null, 'direccion' => null]),
                'datos_nuevos' => json_encode(['telefono' => '987' . str_pad($i, 6, '1', STR_PAD_LEFT), 'direccion' => 'Av. Ejemplo ' . ($i * 100 + 200)]),
                'ip' => '192.168.1.10',
                'created_at' => '2026-03-0' . ($i + 1) . ' 10:30:00',
                'updated_at' => '2026-03-0' . ($i + 1) . ' 10:30:00',
            ];
        }

        // Toggle estado (5 registros)
        $inactivos = $estudiantes->where('estado', 'inactivo')->take(3);
        foreach ($inactivos as $idx => $est) {
            $acciones[] = [
                'user_id' => $admin->id,
                'accion' => 'toggle_estado',
                'modelo' => 'User',
                'modelo_id' => $est->id,
                'datos_anteriores' => json_encode(['estado' => 'activo']),
                'datos_nuevos' => json_encode(['estado' => 'inactivo']),
                'ip' => '192.168.1.10',
                'created_at' => '2026-03-05 14:' . str_pad($idx * 10, 2, '0', STR_PAD_LEFT) . ':00',
                'updated_at' => '2026-03-05 14:' . str_pad($idx * 10, 2, '0', STR_PAD_LEFT) . ':00',
            ];
        }

        $bloqueados = $estudiantes->where('estado', 'bloqueado')->take(2);
        foreach ($bloqueados as $idx => $est) {
            $acciones[] = [
                'user_id' => $admin->id,
                'accion' => 'toggle_estado',
                'modelo' => 'User',
                'modelo_id' => $est->id,
                'datos_anteriores' => json_encode(['estado' => 'activo']),
                'datos_nuevos' => json_encode(['estado' => 'bloqueado']),
                'ip' => '192.168.1.10',
                'created_at' => '2026-03-05 15:' . str_pad($idx * 15, 2, '0', STR_PAD_LEFT) . ':00',
                'updated_at' => '2026-03-05 15:' . str_pad($idx * 15, 2, '0', STR_PAD_LEFT) . ':00',
            ];
        }

        // Creación de exámenes (8 registros, uno por curso)
        foreach ($docentes->take(8) as $idx => $docente) {
            $acciones[] = [
                'user_id' => $docente->id,
                'accion' => 'crear_examen',
                'modelo' => 'Examen',
                'modelo_id' => $idx + 1,
                'datos_anteriores' => null,
                'datos_nuevos' => json_encode(['titulo' => 'Examen Semanal S-01 - ' . $docente->especialidad, 'estado' => 'creado']),
                'ip' => '192.168.1.' . (15 + $idx),
                'created_at' => '2026-03-04 ' . str_pad(16 + ($idx % 3), 2, '0', STR_PAD_LEFT) . ':00:00',
                'updated_at' => '2026-03-04 ' . str_pad(16 + ($idx % 3), 2, '0', STR_PAD_LEFT) . ':00:00',
            ];
        }

        // Cambios de estado de examen (6 registros: publicar + cerrar)
        for ($i = 0; $i < 3; $i++) {
            $docente = $docentes->values()->get($i);
            if (!$docente) continue;

            $acciones[] = [
                'user_id' => $docente->id,
                'accion' => 'actualizar_examen',
                'modelo' => 'Examen',
                'modelo_id' => $i + 1,
                'datos_anteriores' => json_encode(['estado' => 'creado']),
                'datos_nuevos' => json_encode(['estado' => 'publicado']),
                'ip' => '192.168.1.' . (15 + $i),
                'created_at' => '2026-03-06 08:' . str_pad($i * 10, 2, '0', STR_PAD_LEFT) . ':00',
                'updated_at' => '2026-03-06 08:' . str_pad($i * 10, 2, '0', STR_PAD_LEFT) . ':00',
            ];
            $acciones[] = [
                'user_id' => $docente->id,
                'accion' => 'actualizar_examen',
                'modelo' => 'Examen',
                'modelo_id' => $i + 1,
                'datos_anteriores' => json_encode(['estado' => 'publicado']),
                'datos_nuevos' => json_encode(['estado' => 'cerrado']),
                'ip' => '192.168.1.' . (15 + $i),
                'created_at' => '2026-03-08 00:' . str_pad($i * 5, 2, '0', STR_PAD_LEFT) . ':00',
                'updated_at' => '2026-03-08 00:' . str_pad($i * 5, 2, '0', STR_PAD_LEFT) . ':00',
            ];
        }

        // Creación de preguntas (4 registros)
        for ($i = 0; $i < 4; $i++) {
            $docente = $docentes->values()->get($i + 1);
            if (!$docente) continue;

            $acciones[] = [
                'user_id' => $docente->id,
                'accion' => 'crear_pregunta',
                'modelo' => 'Pregunta',
                'modelo_id' => $i + 1,
                'datos_anteriores' => null,
                'datos_nuevos' => json_encode(['texto' => 'Pregunta de ejemplo ' . ($i + 1), 'dificultad' => ['facil', 'medio', 'dificil'][$i % 3]]),
                'ip' => '192.168.1.' . (20 + $i),
                'created_at' => '2026-03-03 11:' . str_pad(30 + $i * 5, 2, '0', STR_PAD_LEFT) . ':00',
                'updated_at' => '2026-03-03 11:' . str_pad(30 + $i * 5, 2, '0', STR_PAD_LEFT) . ':00',
            ];
        }

        // Creación de período
        $acciones[] = [
            'user_id' => $admin->id,
            'accion' => 'crear_periodo',
            'modelo' => 'Periodo',
            'modelo_id' => $periodoActivo->id,
            'datos_anteriores' => null,
            'datos_nuevos' => json_encode(['nombre' => '2026-I', 'estado' => 'activo']),
            'ip' => '192.168.1.10',
            'created_at' => '2026-02-15 09:00:00',
            'updated_at' => '2026-02-15 09:00:00',
        ];

        // Actualización de perfiles por estudiantes (5 registros)
        for ($i = 0; $i < 5; $i++) {
            $est = $estudiantes->values()->get($i * 3);
            if (!$est) continue;

            $acciones[] = [
                'user_id' => $est->id,
                'accion' => 'actualizar_perfil',
                'modelo' => 'User',
                'modelo_id' => $est->id,
                'datos_anteriores' => json_encode(['telefono' => null]),
                'datos_nuevos' => json_encode(['telefono' => '999' . str_pad($i, 6, '8', STR_PAD_LEFT)]),
                'ip' => '192.168.1.' . (50 + $i),
                'created_at' => '2026-03-' . str_pad(10 + $i, 2, '0', STR_PAD_LEFT) . ' 15:45:00',
                'updated_at' => '2026-03-' . str_pad(10 + $i, 2, '0', STR_PAD_LEFT) . ' 15:45:00',
            ];
        }

        Auditoria::insert($acciones);
    }

    private function crearHistorialRoles($admin, $docentes): void
    {
        // 3 docentes que fueron promovidos desde estudiante
        $docentesPromovidos = $docentes->take(3)->reverse();
        $batch = [];

        foreach ($docentesPromovidos as $idx => $docente) {
            $batch[] = [
                'user_id' => $docente->id,
                'rol_anterior' => 'estudiante',
                'rol_nuevo' => 'docente',
                'cambiado_por' => $admin->id,
                'created_at' => '2026-01-' . str_pad(10 + $idx * 5, 2, '0', STR_PAD_LEFT) . ' 10:00:00',
                'updated_at' => '2026-01-' . str_pad(10 + $idx * 5, 2, '0', STR_PAD_LEFT) . ' 10:00:00',
            ];
        }

        HistorialRol::insert($batch);
    }

    /**
     * ~150 observaciones distribuidas entre docentes y estudiantes.
     */
    private function crearObservaciones($docentes, $estudiantes, $cursos): void
    {
        $textos = [
            'Excelente participación en clase. Demuestra comprensión profunda de los temas.',
            'Necesita reforzar los ejercicios prácticos. Se recomienda práctica adicional.',
            'Muy buena actitud y disposición para aprender. Destaca en resolución de problemas.',
            'Presenta dificultades con los temas avanzados. Programar tutoría personalizada.',
            'Ha mejorado notablemente. Su último examen refleja mejor comprensión.',
            'Inasistencias recurrentes. Comunicarse con el apoderado para coordinar seguimiento.',
            'Lidera el grupo de estudio. Ayuda a compañeros con dificultades.',
            'Buen desempeño general. Potencial para concursos académicos.',
            'Entrega puntual de tareas. Necesita mejorar en la redacción de procedimientos.',
            'Muestra interés especial en temas avanzados. Sugerir material complementario.',
            'Bajo rendimiento en evaluaciones. Se recomienda repasar temas fundamentales.',
            'Estudiante responsable. Mejoró su promedio significativamente.',
            'Ganador(a) del concurso interno de la materia. Representará al colegio.',
            'Se observa mejoría después de las tutorías. Continuar con el programa de refuerzo.',
            'Dificultades en la comprensión de problemas tipo admisión. Necesita más simulacros.',
            'Excelente comportamiento y colaboración con sus compañeros.',
            'Participa activamente en las clases virtuales y presenciales.',
            'Tiene potencial sobresaliente pero necesita mayor dedicación en tareas.',
            'Domina los conceptos básicos, debe avanzar a nivel intermedio.',
            'Se recomienda asistir a las sesiones de refuerzo los sábados.',
        ];

        $batch = [];
        $estValues = $estudiantes->where('estado', 'activo')->values();
        $now = now();

        for ($i = 0; $i < 150; $i++) {
            $estudiante = $estValues->get($i % $estValues->count());
            if (!$estudiante) continue;

            $curso = $cursos->values()->get($i % $cursos->count());
            if (!$curso) continue;

            $docente = $curso->docentes->first();
            if (!$docente) continue;

            $batch[] = [
                'docente_id' => $docente->id,
                'estudiante_id' => $estudiante->id,
                'curso_id' => $curso->id,
                'texto' => $textos[$i % count($textos)],
                'created_at' => $now->copy()->subDays($i % 30),
                'updated_at' => $now->copy()->subDays($i % 30),
            ];

            if (count($batch) >= 100) {
                Observacion::insert($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            Observacion::insert($batch);
        }
    }
}
