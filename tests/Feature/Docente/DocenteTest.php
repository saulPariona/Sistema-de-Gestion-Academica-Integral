<?php

namespace Tests\Feature\Docente;

use App\Models\Alternativa;
use App\Models\Curso;
use App\Models\Examen;
use App\Models\Periodo;
use App\Models\Pregunta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocenteTest extends TestCase
{
    use RefreshDatabase;

    private User $docente;
    private Curso $curso;
    private Periodo $periodo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->docente = User::factory()->docente()->create(['estado' => 'activo']);
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
        $this->curso->docentes()->attach($this->docente->id);
    }

    public function test_docente_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->docente)->get(route('docente.dashboard'));
        $response->assertStatus(200);
    }

    public function test_estudiante_cannot_access_docente_dashboard(): void
    {
        $estudiante = User::factory()->estudiante()->create(['estado' => 'activo']);
        $response = $this->actingAs($estudiante)->get(route('docente.dashboard'));
        $response->assertStatus(403);
    }

    public function test_docente_can_view_curso(): void
    {
        $response = $this->actingAs($this->docente)->get(route('docente.curso', $this->curso));
        $response->assertStatus(200);
    }

    public function test_docente_can_create_pregunta(): void
    {
        $response = $this->actingAs($this->docente)->post(
            route('docente.preguntas.guardar', $this->curso),
            [
                'texto' => '¿Cuánto es 2+2?',
                'dificultad' => 'facil',
                'puntaje' => 5,
                'alternativa_correcta' => 1,
                'alternativas' => [
                    ['texto' => '3'],
                    ['texto' => '4'],
                    ['texto' => '5'],
                    ['texto' => '6'],
                ],
            ]
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('preguntas', [
            'texto' => '¿Cuánto es 2+2?',
            'curso_id' => $this->curso->id,
            'docente_id' => $this->docente->id,
        ]);
    }

    public function test_docente_can_create_examen(): void
    {
        $response = $this->actingAs($this->docente)->post(
            route('docente.examenes.guardar', $this->curso),
            [
                'titulo' => 'Examen Parcial',
                'descripcion' => 'Primer examen parcial',
                'puntaje_total' => 20,
                'tiempo_limite' => 60,
                'fecha_inicio' => '2026-04-01 08:00:00',
                'fecha_fin' => '2026-04-01 10:00:00',
                'intentos_permitidos' => 1,
                'orden_aleatorio_preguntas' => true,
                'orden_aleatorio_alternativas' => true,
                'mostrar_resultados' => true,
                'permitir_revision' => false,
                'navegacion_libre' => true,
            ]
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('examenes', [
            'titulo' => 'Examen Parcial',
            'curso_id' => $this->curso->id,
        ]);
    }

    public function test_docente_can_publish_examen(): void
    {
        $examen = Examen::create([
            'curso_id' => $this->curso->id,
            'docente_id' => $this->docente->id,
            'titulo' => 'Examen Test',
            'puntaje_total' => 20,
            'tiempo_limite' => 60,
            'fecha_inicio' => now()->addDay(),
            'fecha_fin' => now()->addDays(2),
            'intentos_permitidos' => 1,
            'estado' => 'creado',
        ]);

        // Assign at least one question
        $pregunta = Pregunta::create([
            'curso_id' => $this->curso->id,
            'docente_id' => $this->docente->id,
            'texto' => '¿Test?',
            'dificultad' => 'facil',
            'puntaje' => 20,
        ]);
        Alternativa::create([
            'pregunta_id' => $pregunta->id,
            'texto' => 'Opción A',
            'es_correcta' => true,
        ]);
        $examen->preguntas()->attach($pregunta->id, ['orden' => 1]);

        $response = $this->actingAs($this->docente)
            ->patch(route('docente.examenes.publicar', [$this->curso, $examen]));

        $response->assertRedirect();
        $examen->refresh();
        $this->assertEquals('publicado', $examen->estado);
    }

    public function test_another_docente_cannot_publish_exam(): void
    {
        $otroDocente = User::factory()->docente()->create(['estado' => 'activo']);

        $examen = Examen::create([
            'curso_id' => $this->curso->id,
            'docente_id' => $this->docente->id,
            'titulo' => 'Examen Ajeno',
            'puntaje_total' => 20,
            'tiempo_limite' => 60,
            'fecha_inicio' => now()->addDay(),
            'fecha_fin' => now()->addDays(2),
            'intentos_permitidos' => 1,
            'estado' => 'creado',
        ]);

        $response = $this->actingAs($otroDocente)
            ->patch(route('docente.examenes.publicar', [$this->curso, $examen]));

        $response->assertStatus(403);
    }

    public function test_docente_can_close_published_examen(): void
    {
        $examen = Examen::create([
            'curso_id' => $this->curso->id,
            'docente_id' => $this->docente->id,
            'titulo' => 'Examen Publicado',
            'puntaje_total' => 20,
            'tiempo_limite' => 60,
            'fecha_inicio' => now()->subDay(),
            'fecha_fin' => now()->addDay(),
            'intentos_permitidos' => 1,
            'estado' => 'publicado',
        ]);

        $response = $this->actingAs($this->docente)
            ->patch(route('docente.examenes.cerrar', [$this->curso, $examen]));

        $response->assertRedirect();
        $examen->refresh();
        $this->assertEquals('cerrado', $examen->estado);
    }
}
