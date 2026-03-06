<?php

namespace Tests\Feature\Estudiante;

use App\Models\Alternativa;
use App\Models\Curso;
use App\Models\Examen;
use App\Models\Intento;
use App\Models\Matricula;
use App\Models\Periodo;
use App\Models\Pregunta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EstudianteTest extends TestCase
{
    use RefreshDatabase;

    private User $estudiante;
    private Curso $curso;
    private Periodo $periodo;
    private Examen $examen;
    private Pregunta $pregunta;
    private Alternativa $alternativaCorrecta;

    protected function setUp(): void
    {
        parent::setUp();

        $this->estudiante = User::factory()->estudiante()->create(['estado' => 'activo']);
        $docente = User::factory()->docente()->create(['estado' => 'activo']);

        $this->periodo = Periodo::create([
            'nombre' => '2026-I',
            'fecha_inicio' => '2026-03-01',
            'fecha_fin' => '2026-07-31',
            'estado' => 'activo',
        ]);
        $this->curso = Curso::create([
            'nombre' => 'Matemática',
            'periodo_id' => $this->periodo->id,
        ]);
        $this->curso->docentes()->attach($docente->id);

        Matricula::create([
            'estudiante_id' => $this->estudiante->id,
            'curso_id' => $this->curso->id,
            'periodo_id' => $this->periodo->id,
            'estado' => 'activa',
        ]);

        $this->pregunta = Pregunta::create([
            'curso_id' => $this->curso->id,
            'docente_id' => $docente->id,
            'texto' => '¿Cuánto es 2+2?',
            'dificultad' => 'facil',
            'puntaje' => 20,
        ]);

        $this->alternativaCorrecta = Alternativa::create([
            'pregunta_id' => $this->pregunta->id,
            'texto' => '4',
            'es_correcta' => true,
        ]);
        Alternativa::create([
            'pregunta_id' => $this->pregunta->id,
            'texto' => '3',
            'es_correcta' => false,
        ]);

        $this->examen = Examen::create([
            'curso_id' => $this->curso->id,
            'docente_id' => $docente->id,
            'titulo' => 'Examen Parcial',
            'puntaje_total' => 20,
            'tiempo_limite' => 60,
            'fecha_inicio' => now()->subHour(),
            'fecha_fin' => now()->addHours(2),
            'intentos_permitidos' => 2,
            'estado' => 'publicado',
        ]);
        $this->examen->preguntas()->attach($this->pregunta->id, ['orden' => 1]);
    }

    public function test_estudiante_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->estudiante)->get(route('estudiante.dashboard'));
        $response->assertStatus(200);
    }

    public function test_docente_cannot_access_estudiante_dashboard(): void
    {
        $docente = User::factory()->docente()->create(['estado' => 'activo']);
        $response = $this->actingAs($docente)->get(route('estudiante.dashboard'));
        $response->assertStatus(403);
    }

    public function test_estudiante_can_see_available_exams(): void
    {
        $response = $this->actingAs($this->estudiante)
            ->get(route('estudiante.examenes', $this->curso));
        $response->assertStatus(200);
        $response->assertSee('Examen Parcial');
    }

    public function test_estudiante_can_start_exam(): void
    {
        $response = $this->actingAs($this->estudiante)
            ->post(route('estudiante.iniciar-examen', [$this->curso, $this->examen]));

        $response->assertRedirect();
        $this->assertDatabaseHas('intentos', [
            'examen_id' => $this->examen->id,
            'estudiante_id' => $this->estudiante->id,
            'numero_intento' => 1,
            'estado' => 'en_progreso',
        ]);
    }

    public function test_estudiante_cannot_exceed_max_attempts(): void
    {
        // Create 2 finished attempts (max allowed)
        for ($i = 1; $i <= 2; $i++) {
            Intento::create([
                'examen_id' => $this->examen->id,
                'estudiante_id' => $this->estudiante->id,
                'numero_intento' => $i,
                'inicio' => now()->subHour(),
                'fin' => now(),
                'puntaje_obtenido' => 15,
                'estado' => 'finalizado',
            ]);
        }

        $response = $this->actingAs($this->estudiante)
            ->post(route('estudiante.iniciar-examen', [$this->curso, $this->examen]));

        // Policy denies with 403 since max attempts exhausted
        $response->assertStatus(403);
    }

    public function test_estudiante_can_save_answer(): void
    {
        $intento = Intento::create([
            'examen_id' => $this->examen->id,
            'estudiante_id' => $this->estudiante->id,
            'numero_intento' => 1,
            'inicio' => now(),
            'estado' => 'en_progreso',
        ]);

        $response = $this->actingAs($this->estudiante)
            ->post(route('estudiante.guardar-respuesta', [$this->curso, $this->examen, $intento]), [
                'pregunta_id' => $this->pregunta->id,
                'alternativa_id' => $this->alternativaCorrecta->id,
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('respuestas', [
            'intento_id' => $intento->id,
            'pregunta_id' => $this->pregunta->id,
            'alternativa_id' => $this->alternativaCorrecta->id,
        ]);
    }

    public function test_estudiante_can_finish_exam(): void
    {
        $intento = Intento::create([
            'examen_id' => $this->examen->id,
            'estudiante_id' => $this->estudiante->id,
            'numero_intento' => 1,
            'inicio' => now(),
            'estado' => 'en_progreso',
        ]);

        $response = $this->actingAs($this->estudiante)
            ->post(route('estudiante.finalizar-examen', [$this->curso, $this->examen, $intento]));

        $response->assertRedirect();
        $intento->refresh();
        $this->assertEquals('finalizado', $intento->estado);
    }

    public function test_non_enrolled_student_cannot_access_exams(): void
    {
        $otroEstudiante = User::factory()->estudiante()->create(['estado' => 'activo']);

        $response = $this->actingAs($otroEstudiante)
            ->get(route('estudiante.examenes', $this->curso));

        $response->assertStatus(403);
    }

    public function test_estudiante_can_view_profile(): void
    {
        $response = $this->actingAs($this->estudiante)
            ->get(route('estudiante.perfil'));
        $response->assertStatus(200);
    }
}
