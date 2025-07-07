<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Executar seeders
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
    }

    /** @test */
    public function it_redirects_unauthenticated_users_to_login()
    {
        $response = $this->get('/home');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function it_shows_dashboard_for_authenticated_users()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/home');

        $response->assertStatus(200);
        $response->assertViewIs('home');
    }

    /** @test */
    public function it_works_with_different_user_roles()
    {
        // Testar com usuário admin
        $adminUser = User::factory()->create();
        $adminUser->assignRole('admin');

        $this->actingAs($adminUser)
            ->get('/home')
            ->assertStatus(200);

        // Testar com usuário regular
        $regularUser = User::factory()->create();
        $regularUser->assignRole('user');

        $this->actingAs($regularUser)
            ->get('/home')
            ->assertStatus(200);

        // Testar com usuário sem role
        $userWithoutRole = User::factory()->create();

        $this->actingAs($userWithoutRole)
            ->get('/home')
            ->assertStatus(200);
    }

    /** @test */
    public function it_handles_session_expiration()
    {
        // Criar usuário e fazer login
        $user = User::factory()->create();
        $user->assignRole('user');

        $this->actingAs($user)
            ->get('/home')
            ->assertStatus(200);

        // Fazer logout
        $this->post('/logout');

        // Tentar acessar novamente - deve redirecionar para login
        $this->get('/home')
            ->assertRedirect('/login');
    }
}
