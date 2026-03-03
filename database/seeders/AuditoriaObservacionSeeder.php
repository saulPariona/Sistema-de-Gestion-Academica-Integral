<?php

namespace Database\Seeders;

use App\Models\Auditoria;
use App\Models\HistorialRol;
use App\Models\Observacion;
use App\Models\User;
use App\Models\Curso;
use App\Models\Periodo;
use Illuminate\Database\Seeder;

class AuditoriaObservacionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('rol', 'administrador')->first();
        $docentes = User::where('rol', 'docente')->get();
        $estudiantes = User::where('rol', 'estudiante')->get();
        $periodoActivo = Periodo::where('nombre', '2026-I')->first();
        $cursos = Curso::where('periodo_id', $periodoActivo->id)->with('docentes')->get();

        // ==================== AUDITORÍAS ====================
        $acciones = [
            [
                'user_id' => $admin->id,
                'accion' => 'crear_usuario',
                'modelo' => 'User',
                'modelo_id' => $estudiantes->first()->id,
                'datos_anteriores' => null,
                'datos_nuevos' => ['nombres' => 'María Fernanda', 'apellidos' => 'Pérez Sánchez', 'rol' => 'estudiante', 'estado' => 'activo'],
                'ip' => '192.168.1.10',
                'created_at' => '2026-02-25 09:15:00',
            ],
            [
                'user_id' => $admin->id,
                'accion' => 'crear_usuario',
                'modelo' => 'User',
                'modelo_id' => $estudiantes->skip(1)->first()->id,
                'datos_anteriores' => null,
                'datos_nuevos' => ['nombres' => 'Juan Diego', 'apellidos' => 'López Torres', 'rol' => 'estudiante', 'estado' => 'activo'],
                'ip' => '192.168.1.10',
                'created_at' => '2026-02-25 09:20:00',
            ],
            [
                'user_id' => $admin->id,
                'accion' => 'actualizar_usuario',
                'modelo' => 'User',
                'modelo_id' => $estudiantes->skip(5)->first()->id,
                'datos_anteriores' => ['telefono' => null, 'direccion' => null],
                'datos_nuevos' => ['telefono' => '987111222', 'direccion' => 'Av. Javier Prado 1200, San Isidro'],
                'ip' => '192.168.1.10',
                'created_at' => '2026-03-02 10:30:00',
            ],
            [
                'user_id' => $admin->id,
                'accion' => 'toggle_estado',
                'modelo' => 'User',
                'modelo_id' => $estudiantes->skip(40)->first()?->id ?? $estudiantes->last()->id,
                'datos_anteriores' => ['estado' => 'activo'],
                'datos_nuevos' => ['estado' => 'inactivo'],
                'ip' => '192.168.1.10',
                'created_at' => '2026-03-05 14:00:00',
            ],
            [
                'user_id' => $docentes->first()->id,
                'accion' => 'crear_examen',
                'modelo' => 'Examen',
                'modelo_id' => 1,
                'datos_anteriores' => null,
                'datos_nuevos' => ['titulo' => 'Examen Semanal S-01 - Álgebra', 'estado' => 'creado'],
                'ip' => '192.168.1.15',
                'created_at' => '2026-03-04 16:00:00',
            ],
            [
                'user_id' => $docentes->first()->id,
                'accion' => 'actualizar_examen',
                'modelo' => 'Examen',
                'modelo_id' => 1,
                'datos_anteriores' => ['estado' => 'creado'],
                'datos_nuevos' => ['estado' => 'publicado'],
                'ip' => '192.168.1.15',
                'created_at' => '2026-03-06 08:00:00',
            ],
            [
                'user_id' => $docentes->first()->id,
                'accion' => 'actualizar_examen',
                'modelo' => 'Examen',
                'modelo_id' => 1,
                'datos_anteriores' => ['estado' => 'publicado'],
                'datos_nuevos' => ['estado' => 'cerrado'],
                'ip' => '192.168.1.15',
                'created_at' => '2026-03-08 00:00:00',
            ],
            [
                'user_id' => $docentes->skip(1)->first()->id,
                'accion' => 'crear_pregunta',
                'modelo' => 'Pregunta',
                'modelo_id' => 1,
                'datos_anteriores' => null,
                'datos_nuevos' => ['texto' => 'Halla el MCD de 36 y 48.', 'dificultad' => 'facil'],
                'ip' => '192.168.1.20',
                'created_at' => '2026-03-03 11:30:00',
            ],
            [
                'user_id' => $admin->id,
                'accion' => 'crear_periodo',
                'modelo' => 'Periodo',
                'modelo_id' => $periodoActivo->id,
                'datos_anteriores' => null,
                'datos_nuevos' => ['nombre' => '2026-I', 'estado' => 'activo'],
                'ip' => '192.168.1.10',
                'created_at' => '2026-02-15 09:00:00',
            ],
            [
                'user_id' => $estudiantes->first()->id,
                'accion' => 'actualizar_perfil',
                'modelo' => 'User',
                'modelo_id' => $estudiantes->first()->id,
                'datos_anteriores' => ['telefono' => null],
                'datos_nuevos' => ['telefono' => '999888777'],
                'ip' => '192.168.1.50',
                'created_at' => '2026-03-10 15:45:00',
            ],
            [
                'user_id' => $docentes->skip(3)->first()->id,
                'accion' => 'crear_examen',
                'modelo' => 'Examen',
                'modelo_id' => 2,
                'datos_anteriores' => null,
                'datos_nuevos' => ['titulo' => 'Examen Semanal S-01 - Trigonometría', 'estado' => 'creado'],
                'ip' => '192.168.1.25',
                'created_at' => '2026-03-04 17:30:00',
            ],
            [
                'user_id' => $admin->id,
                'accion' => 'crear_curso',
                'modelo' => 'Curso',
                'modelo_id' => 1,
                'datos_anteriores' => null,
                'datos_nuevos' => ['nombre' => 'Álgebra', 'periodo' => '2026-I'],
                'ip' => '192.168.1.10',
                'created_at' => '2026-02-20 10:00:00',
            ],
            [
                'user_id' => $docentes->skip(4)->first()->id,
                'accion' => 'actualizar_pregunta',
                'modelo' => 'Pregunta',
                'modelo_id' => 5,
                'datos_anteriores' => ['texto' => 'Un cuerpo cae desde 45 m...', 'dificultad' => 'facil'],
                'datos_nuevos' => ['texto' => 'Un cuerpo cae libremente desde 45 m de altura. ¿Cuánto tarda en llegar al suelo? (g = 10 m/s²)', 'dificultad' => 'medio'],
                'ip' => '192.168.1.30',
                'created_at' => '2026-03-06 12:15:00',
            ],
            [
                'user_id' => $admin->id,
                'accion' => 'actualizar_usuario',
                'modelo' => 'User',
                'modelo_id' => $docentes->skip(2)->first()->id,
                'datos_anteriores' => ['grado_academico' => 'Licenciado en Geometría'],
                'datos_nuevos' => ['grado_academico' => 'Magíster en Didáctica de la Matemática'],
                'ip' => '192.168.1.10',
                'created_at' => '2026-03-01 09:00:00',
            ],
            [
                'user_id' => $estudiantes->skip(2)->first()->id,
                'accion' => 'actualizar_perfil',
                'modelo' => 'User',
                'modelo_id' => $estudiantes->skip(2)->first()->id,
                'datos_anteriores' => ['direccion' => null],
                'datos_nuevos' => ['direccion' => 'Jr. Huallaga 350, Cercado de Lima'],
                'ip' => '192.168.1.55',
                'created_at' => '2026-03-12 16:20:00',
            ],
        ];

        foreach ($acciones as $accion) {
            Auditoria::create($accion);
        }

        // ==================== HISTORIAL DE ROLES ====================
        // Un docente que antes era estudiante
        $docentePromovido = $docentes->last();
        HistorialRol::create([
            'user_id' => $docentePromovido->id,
            'rol_anterior' => 'estudiante',
            'rol_nuevo' => 'docente',
            'cambiado_por' => $admin->id,
            'created_at' => '2026-01-15 10:00:00',
        ]);

        // ==================== OBSERVACIONES ====================
        $observacionesData = [
            ['estudiante_idx' => 0, 'texto' => 'Excelente participación en clase. Demuestra comprensión profunda de los temas de ecuaciones.'],
            ['estudiante_idx' => 1, 'texto' => 'Necesita reforzar el tema de factorización. Se recomienda práctica adicional.'],
            ['estudiante_idx' => 2, 'texto' => 'Muy buena actitud y disposición para aprender. Destaca en resolución de problemas.'],
            ['estudiante_idx' => 3, 'texto' => 'Presenta dificultades con las identidades trigonométricas. Programar tutoría personalizada.'],
            ['estudiante_idx' => 4, 'texto' => 'Ha mejorado notablemente en física. Su último examen refleja mejor comprensión.'],
            ['estudiante_idx' => 5, 'texto' => 'Inasistencias recurrentes. Comunicarse con el apoderado para coordinar seguimiento.'],
            ['estudiante_idx' => 6, 'texto' => 'Lidera el grupo de estudio. Ayuda a compañeros con dificultades en aritmética.'],
            ['estudiante_idx' => 7, 'texto' => 'Buen desempeño en razonamiento verbal. Potencial para concursos de comprensión lectora.'],
            ['estudiante_idx' => 8, 'texto' => 'Entrega puntual de tareas. Necesita mejorar en la redacción de procedimientos.'],
            ['estudiante_idx' => 9, 'texto' => 'Muestra interés especial en química orgánica. Sugerir material complementario.'],
            ['estudiante_idx' => 10, 'texto' => 'Bajo rendimiento en geometría. Se recomienda repasar propiedades de triángulos y cuadriláteros.'],
            ['estudiante_idx' => 11, 'texto' => 'Estudiante responsable. Mejoró su promedio de 12 a 16 en el último mes.'],
            ['estudiante_idx' => 0, 'texto' => 'Ganadora del concurso interno de matemáticas. Representará al colegio en la olimpiada regional.'],
            ['estudiante_idx' => 3, 'texto' => 'Se observa mejoría después de las tutorías. Continuar con el programa de refuerzo.'],
            ['estudiante_idx' => 15, 'texto' => 'Dificultades en la comprensión de problemas tipo admisión. Sugerir más práctica con simulacros.'],
        ];

        foreach ($observacionesData as $obsData) {
            $estudiante = $estudiantes->values()->get($obsData['estudiante_idx']);
            if (!$estudiante) continue;

            // Elegir un curso y su docente al azar
            $curso = $cursos->random();
            $docente = $curso->docentes->first();
            if (!$docente) continue;

            Observacion::create([
                'docente_id' => $docente->id,
                'estudiante_id' => $estudiante->id,
                'curso_id' => $curso->id,
                'texto' => $obsData['texto'],
            ]);
        }
    }
}
