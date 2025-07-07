<?php

namespace Tests\Feature\Middleware;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CheckPermissionMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Executar seeders
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
    }

    /** @test */
    public function it_returns_401_for_unauthenticated_users()
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get('/test-route');

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Usuário não autenticado']);
    }

    /** @test */
    public function it_allows_access_for_users_with_permission()
    {
        $user = User::factory()->create();
        $permission = Permission::create(['name' => 'test-permission']);
        $user->givePermissionTo($permission);

        $response = $this->actingAs($user)
            ->get('/test-route');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_returns_403_for_users_without_permission()
    {
        $user = User::factory()->create();

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->actingAs($user)
            ->get('/test-route');

        $response->assertStatus(403);
        $response->assertJson(['message' => 'Acesso negado - Permissão insuficiente']);
    }

    /** @test */
    public function it_works_with_direct_permission_assignment()
    {
        $user = User::factory()->create();
        $permission = Permission::create(['name' => 'test-permission']);
        $user->givePermissionTo($permission);

        $response = $this->actingAs($user)
            ->get('/test-route');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_handles_multiple_permissions()
    {
        $user = User::factory()->create();
        $permission1 = Permission::create(['name' => 'permission-1']);
        $permission2 = Permission::create(['name' => 'permission-2']);
        $user->givePermissionTo([$permission1, $permission2]);

        // Testar com permission-1
        $response = $this->actingAs($user)
            ->get('/test-route-multiple');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_works_with_admin_role()
    {
        $user = User::factory()->create();
        $adminRole = Role::where('name', 'admin')->first();
        $user->assignRole($adminRole);

        // Dar a permissão específica ao usuário admin
        $permission = Permission::create(['name' => 'test-permission']);
        $user->givePermissionTo($permission);

        $response = $this->actingAs($user)
            ->get('/test-route');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_handles_session_expiration_during_request()
    {
        $user = User::factory()->create();
        $permission = Permission::create(['name' => 'test-permission']);
        $user->givePermissionTo($permission);

        // Fazer request autenticado
        $this->actingAs($user)
            ->get('/test-route')
            ->assertStatus(200);

        // Fazer logout
        $this->post('/logout');

        $this->withHeaders(['Accept' => 'application/json'])
            ->get('/test-route')
            ->assertStatus(401);
    }

    /** @test */
    public function it_works_with_different_http_methods()
    {
        $user = User::factory()->create();
        $permission = Permission::create(['name' => 'test-permission']);
        $user->givePermissionTo($permission);

        // Testar diferentes métodos HTTP
        $this->actingAs($user)
            ->post('/test-route')
            ->assertStatus(405); // Method Not Allowed

        $this->actingAs($user)
            ->put('/test-route')
            ->assertStatus(405); // Method Not Allowed

        $this->actingAs($user)
            ->delete('/test-route')
            ->assertStatus(405); // Method Not Allowed
    }

    /** @test */
    public function it_handles_user_without_roles()
    {
        $user = User::factory()->create();

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->actingAs($user)
            ->get('/test-route');

        $response->assertStatus(403);
    }

    /** @test */
    public function it_handles_user_with_role_but_no_permission()
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'regular-user']);
        $user->assignRole($role);

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->actingAs($user)
            ->get('/test-route');

        $response->assertStatus(403);
    }
}
