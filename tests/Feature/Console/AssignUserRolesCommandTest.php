<?php

namespace Tests\Feature\Console;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AssignUserRolesCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Executar seeder de permissões
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
    }

    /** @test */
    public function it_assigns_default_roles_to_users_without_roles()
    {
        // Criar usuários sem roles
        $user1 = User::factory()->create(['email' => 'user1@example.com']);
        $user2 = User::factory()->create(['email' => 'user2@example.com']);

        $this->artisan('users:assign-roles')
            ->expectsOutputToContain('Verificando usuários sem roles...')
            ->assertExitCode(0);
        $this->assertTrue($user1->fresh()->hasRole('user'));
        $this->assertTrue($user2->fresh()->hasRole('user'));
    }

    /** @test */
    public function it_assigns_admin_role_to_users_with_admin_email()
    {
        // Criar usuários com emails que contêm "admin"
        $adminUser = User::factory()->create(['email' => 'admin@example.com']);
        $regularUser = User::factory()->create(['email' => 'user@example.com']);

        $this->artisan('users:assign-roles')
            ->expectsOutputToContain('Verificando usuários sem roles...')
            ->assertExitCode(0);
        $this->assertTrue($adminUser->fresh()->hasRole('admin'));
        $this->assertTrue($regularUser->fresh()->hasRole('user'));
    }

    /** @test */
    public function it_assigns_custom_default_role()
    {
        // Criar role customizada
        $managerRole = Role::create(['name' => 'manager']);
        // Limpar cache de permissões
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Criar usuário sem role (garantir que não tenha role)
        $user = User::factory()->create(['email' => 'manager@example.com']);
        // Remover qualquer role que possa ter sido atribuída
        $user->syncRoles([]);

        $this->artisan('users:assign-roles', ['--role' => 'manager'])
            ->expectsOutputToContain('Verificando usuários sem roles...')
            ->assertExitCode(0);
        $this->assertTrue($user->fresh()->hasRole('manager'));
    }

    /** @test */
    public function it_returns_error_when_role_does_not_exist()
    {
        // Criar usuário sem role para garantir que o comando tente atribuir a role inexistente
        $user = User::factory()->create(['email' => 'test@example.com']);
        $user->syncRoles([]);

        $this->artisan('users:assign-roles', ['--role' => 'nonexistent'])
            ->expectsOutputToContain('Verificando usuários sem roles...')
            ->expectsOutputToContain("Role 'nonexistent' não existe!")
            ->assertExitCode(1);
    }

    /** @test */
    public function it_shows_summary_of_assigned_roles()
    {
        // Criar usuários
        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com'
        ]);
        $regularUser = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ]);

        $this->artisan('users:assign-roles')
            ->expectsOutputToContain('Verificando usuários sem roles...')
            ->assertExitCode(0);
        $this->assertTrue($adminUser->fresh()->hasRole('admin'));
        $this->assertTrue($regularUser->fresh()->hasRole('user'));
    }

    /** @test */
    public function it_handles_case_insensitive_admin_email_check()
    {
        // Criar usuários com variações de "admin" no email
        $adminUser1 = User::factory()->create(['email' => 'ADMIN@example.com']);
        $adminUser2 = User::factory()->create(['email' => 'myadmin@example.com']);
        $regularUser = User::factory()->create(['email' => 'user@example.com']);

        $this->artisan('users:assign-roles')
            ->expectsOutputToContain('Verificando usuários sem roles...')
            ->assertExitCode(0);
        $this->assertTrue($adminUser1->fresh()->hasRole('admin'));
        $this->assertTrue($adminUser2->fresh()->hasRole('admin'));
        $this->assertTrue($regularUser->fresh()->hasRole('user'));
    }

    /** @test */
    public function it_handles_users_with_existing_roles()
    {
        // Criar usuário com role já atribuída
        $userWithRole = User::factory()->create();
        $userWithRole->assignRole('user');

        $this->artisan('users:assign-roles')
            ->expectsOutputToContain('Verificando usuários sem roles...')
            ->assertExitCode(0);
    }
}
