<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Periodo;
use App\Models\Curso;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->administrador()->create(['estado' => 'activo']);
    }

    public function test_admin_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_admin_dashboard(): void
    {
        $docente = User::factory()->docente()->create(['estado' => 'activo']);
        $response = $this->actingAs($docente)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    public function test_admin_can_list_users(): void
    {
        User::factory()->count(5)->create(['estado' => 'activo']);

        $response = $this->actingAs($this->admin)->get(route('admin.usuarios'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_user(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.usuarios.guardar'), [
            'nombres' => 'Juan',
            'apellidos' => 'Pérez López',
            'dni' => '12345678',
            'email' => 'juan.perez@test.com',
            'password' => 'Temporal1',
            'rol' => 'estudiante',
            'estado' => 'activo',
            'sexo' => 'M',
            'fecha_nacimiento' => '2010-05-15',
        ]);

        $response->assertRedirect(route('admin.usuarios'));
        $this->assertDatabaseHas('users', ['dni' => '12345678']);
    }

    public function test_admin_can_toggle_user_status(): void
    {
        $user = User::factory()->create(['estado' => 'activo']);

        $this->actingAs($this->admin)->patch(route('admin.usuarios.toggle', $user));

        $user->refresh();
        $this->assertEquals('inactivo', $user->estado);
    }

    public function test_admin_can_create_periodo(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.periodos.guardar'), [
            'nombre' => '2026-I',
            'fecha_inicio' => '2026-03-01',
            'fecha_fin' => '2026-07-31',
            'estado' => 'activo',
        ]);

        $response->assertRedirect(route('admin.periodos'));
        $this->assertDatabaseHas('periodos', ['nombre' => '2026-I']);
    }

    public function test_admin_can_create_curso(): void
    {
        $periodo = Periodo::create([
            'nombre' => '2026-I',
            'fecha_inicio' => '2026-03-01',
            'fecha_fin' => '2026-07-31',
            'estado' => 'activo',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.cursos.guardar'), [
            'nombre' => 'Matemática',
            'descripcion' => 'Curso de matemática básica',
            'periodo_id' => $periodo->id,
        ]);

        $response->assertRedirect(route('admin.cursos'));
        $this->assertDatabaseHas('cursos', ['nombre' => 'Matemática']);
    }

    public function test_admin_can_create_matricula(): void
    {
        $estudiante = User::factory()->estudiante()->create(['estado' => 'activo']);
        $periodo = Periodo::create([
            'nombre' => '2026-I',
            'fecha_inicio' => '2026-03-01',
            'fecha_fin' => '2026-07-31',
            'estado' => 'activo',
        ]);
        $curso = Curso::create([
            'nombre' => 'Matemática',
            'periodo_id' => $periodo->id,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.matriculas.guardar'), [
            'estudiante_id' => $estudiante->id,
            'curso_id' => $curso->id,
            'periodo_id' => $periodo->id,
            'estado' => 'activa',
        ]);

        $response->assertRedirect(route('admin.matriculas'));
        $this->assertDatabaseHas('matriculas', [
            'estudiante_id' => $estudiante->id,
            'curso_id' => $curso->id,
        ]);
    }

    public function test_admin_cannot_create_duplicate_matricula(): void
    {
        $estudiante = User::factory()->estudiante()->create(['estado' => 'activo']);
        $periodo = Periodo::create([
            'nombre' => '2026-I',
            'fecha_inicio' => '2026-03-01',
            'fecha_fin' => '2026-07-31',
            'estado' => 'activo',
        ]);
        $curso = Curso::create([
            'nombre' => 'Matemática',
            'periodo_id' => $periodo->id,
        ]);

        // Primera matrícula
        $this->actingAs($this->admin)->post(route('admin.matriculas.guardar'), [
            'estudiante_id' => $estudiante->id,
            'curso_id' => $curso->id,
            'periodo_id' => $periodo->id,
            'estado' => 'activa',
        ]);

        // Intento de duplicado
        $response = $this->actingAs($this->admin)->post(route('admin.matriculas.guardar'), [
            'estudiante_id' => $estudiante->id,
            'curso_id' => $curso->id,
            'periodo_id' => $periodo->id,
            'estado' => 'activa',
        ]);

        $response->assertSessionHas('error');
    }

    public function test_admin_can_access_auditorias(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.auditorias'));
        $response->assertStatus(200);
    }
}
