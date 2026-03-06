<?php

namespace Tests\Feature\Policies;

use App\Models\Curso;
use App\Models\Examen;
use App\Models\Matricula;
use App\Models\Periodo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PolicyTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $docente;
    private User $estudiante;
    private Curso $curso;
    private Examen $examen;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->administrador()->create(['estado' => 'activo']);
        $this->docente = User::factory()->docente()->create(['estado' => 'activo']);
        $this->estudiante = User::factory()->estudiante()->create(['estado' => 'activo']);

        $periodo = Periodo::create([
            'nombre' => '2026-I',
            'fecha_inicio' => '2026-03-01',
            'fecha_fin' => '2026-07-31',
            'estado' => 'activo',
        ]);
        $this->curso = Curso::create([
            'nombre' => 'Matemática',
            'periodo_id' => $periodo->id,
        ]);
        $this->curso->docentes()->attach($this->docente->id);
        Matricula::create([
            'estudiante_id' => $this->estudiante->id,
            'curso_id' => $this->curso->id,
            'periodo_id' => $periodo->id,
            'estado' => 'activa',
        ]);

        $this->examen = Examen::create([
            'curso_id' => $this->curso->id,
            'docente_id' => $this->docente->id,
            'titulo' => 'Examen Test',
            'puntaje_total' => 20,
            'tiempo_limite' => 60,
            'fecha_inicio' => now()->subHour(),
            'fecha_fin' => now()->addHours(2),
            'intentos_permitidos' => 1,
            'estado' => 'publicado',
        ]);
    }

    // ─── CursoPolicy ─────────────────────────────────────────
    public function test_admin_can_create_curso(): void
    {
        $this->assertTrue($this->admin->can('create', Curso::class));
    }

    public function test_docente_cannot_create_curso(): void
    {
        $this->assertFalse($this->docente->can('create', Curso::class));
    }

    public function test_admin_can_view_any_curso(): void
    {
        $this->assertTrue($this->admin->can('view', $this->curso));
    }

    public function test_docente_can_view_assigned_curso(): void
    {
        $this->assertTrue($this->docente->can('view', $this->curso));
    }

    public function test_docente_cannot_view_unassigned_curso(): void
    {
        $otroCurso = Curso::create(['nombre' => 'Otro', 'periodo_id' => $this->curso->periodo_id]);
        $this->assertFalse($this->docente->can('view', $otroCurso));
    }

    public function test_enrolled_student_can_view_curso(): void
    {
        $this->assertTrue($this->estudiante->can('view', $this->curso));
    }

    public function test_non_enrolled_student_cannot_view_curso(): void
    {
        $otroEstudiante = User::factory()->estudiante()->create(['estado' => 'activo']);
        $this->assertFalse($otroEstudiante->can('view', $this->curso));
    }

    // ─── ExamenPolicy ────────────────────────────────────────
    public function test_docente_can_create_examen(): void
    {
        $this->assertTrue($this->docente->can('create', Examen::class));
    }

    public function test_estudiante_cannot_create_examen(): void
    {
        $this->assertFalse($this->estudiante->can('create', Examen::class));
    }

    public function test_owner_docente_can_update_examen(): void
    {
        $this->assertTrue($this->docente->can('update', $this->examen));
    }

    public function test_other_docente_cannot_update_examen(): void
    {
        $otroDocente = User::factory()->docente()->create(['estado' => 'activo']);
        $this->assertFalse($otroDocente->can('update', $this->examen));
    }

    public function test_enrolled_student_can_take_active_examen(): void
    {
        $this->assertTrue($this->estudiante->can('rendir', $this->examen));
    }

    public function test_non_enrolled_student_cannot_take_examen(): void
    {
        $otro = User::factory()->estudiante()->create(['estado' => 'activo']);
        $this->assertFalse($otro->can('rendir', $this->examen));
    }

    public function test_cannot_take_closed_examen(): void
    {
        $this->examen->update(['estado' => 'cerrado']);
        $this->assertFalse($this->estudiante->can('rendir', $this->examen));
    }

    public function test_docente_can_publish_own_created_examen(): void
    {
        $examenCreado = Examen::create([
            'curso_id' => $this->curso->id,
            'docente_id' => $this->docente->id,
            'titulo' => 'Otro Examen',
            'puntaje_total' => 20,
            'tiempo_limite' => 60,
            'fecha_inicio' => now()->addDay(),
            'fecha_fin' => now()->addDays(2),
            'intentos_permitidos' => 1,
            'estado' => 'creado',
        ]);
        $this->assertTrue($this->docente->can('publicar', $examenCreado));
    }

    public function test_docente_cannot_publish_already_published_examen(): void
    {
        $this->assertFalse($this->docente->can('publicar', $this->examen));
    }

    public function test_admin_can_view_any_exam_results(): void
    {
        $this->assertTrue($this->admin->can('verResultados', $this->examen));
    }

    public function test_owner_docente_can_view_exam_results(): void
    {
        $this->assertTrue($this->docente->can('verResultados', $this->examen));
    }

    public function test_student_cannot_view_exam_results_as_docente(): void
    {
        $this->assertFalse($this->estudiante->can('verResultados', $this->examen));
    }
}
