<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_loads(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('Admin1234'),
            'rol' => 'administrador',
            'estado' => 'activo',
        ]);

        $response = $this->post('/', [
            'email' => $user->email,
            'password' => 'Admin1234',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_wrong_password(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('Admin1234'),
            'estado' => 'activo',
        ]);

        $response = $this->post('/', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();
    }

    public function test_inactive_user_cannot_login(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('Admin1234'),
            'estado' => 'inactivo',
        ]);

        $response = $this->post('/', [
            'email' => $user->email,
            'password' => 'Admin1234',
        ]);

        $this->assertGuest();
        $response->assertSessionHas('error');
    }

    public function test_blocked_user_cannot_login(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('Admin1234'),
            'estado' => 'bloqueado',
            'bloqueado_hasta' => now()->addMinutes(30),
        ]);

        $response = $this->post('/', [
            'email' => $user->email,
            'password' => 'Admin1234',
        ]);

        $this->assertGuest();
        $response->assertSessionHas('error');
    }

    public function test_user_is_blocked_after_five_failed_attempts(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('Admin1234'),
            'estado' => 'activo',
            'intentos_fallidos' => 4,
        ]);

        $this->post('/', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $user->refresh();
        $this->assertEquals('bloqueado', $user->estado);
        $this->assertNotNull($user->bloqueado_hasta);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create(['estado' => 'activo']);

        $this->actingAs($user)
            ->get('/logout')
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_login_redirects_docente_to_docente_dashboard(): void
    {
        $user = User::factory()->docente()->create([
            'password' => bcrypt('Docente1234'),
            'estado' => 'activo',
        ]);

        $response = $this->post('/', [
            'email' => $user->email,
            'password' => 'Docente1234',
        ]);

        $response->assertRedirect(route('docente.dashboard'));
    }

    public function test_login_redirects_estudiante_to_estudiante_dashboard(): void
    {
        $user = User::factory()->estudiante()->create([
            'password' => bcrypt('Estudiante1234'),
            'estado' => 'activo',
        ]);

        $response = $this->post('/', [
            'email' => $user->email,
            'password' => 'Estudiante1234',
        ]);

        $response->assertRedirect(route('estudiante.dashboard'));
    }
}
