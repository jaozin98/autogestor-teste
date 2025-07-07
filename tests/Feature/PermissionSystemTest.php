<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Criar permissões básicas
        Permission::create(['name' => 'products.view']);
        Permission::create(['name' => 'products.create']);
        Permission::create(['name' => 'users.view']);
        Permission::create(['name' => 'users.create']);

        // Criar roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // Atribuir permissões
        $adminRole->givePermissionTo(Permission::all());
        $userRole->givePermissionTo(['products.view']);
    }

    public function test_admin_can_access_roles_index(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/roles');

        $response->assertStatus(200);
        $response->assertSee('Gerenciar Roles');
    }

    public function test_admin_can_access_permissions_index(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/permissions');

        $response->assertStatus(200);
        $response->assertSee('Gerenciar Permissões');
    }

    public function test_user_cannot_access_roles_index(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)->get('/roles');

        $response->assertStatus(403);
    }

    public function test_user_cannot_access_permissions_index(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)->get('/permissions');

        $response->assertStatus(403);
    }

    public function test_admin_can_create_role(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post('/roles', [
            'name' => 'editor',
            'permissions' => ['products.view', 'products.create']
        ]);

        $response->assertRedirect('/roles');
        $this->assertDatabaseHas('roles', ['name' => 'editor']);

        $role = Role::where('name', 'editor')->first();
        $this->assertTrue($role->hasPermissionTo('products.view'));
        $this->assertTrue($role->hasPermissionTo('products.create'));
    }

    public function test_admin_can_create_permission(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post('/permissions', [
            'name' => 'products.export'
        ]);

        $response->assertRedirect('/permissions');
        $this->assertDatabaseHas('permissions', ['name' => 'products.export']);
    }

    public function test_permissions_are_dynamic_from_database(): void
    {
        // Criar permissão dinamicamente
        Permission::create(['name' => 'custom.permission']);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // Atribuir a nova permissão ao role admin
        $adminRole = Role::where('name', 'admin')->first();
        $adminRole->givePermissionTo('custom.permission');

        // Verificar se a permissão está disponível
        $this->assertTrue($admin->can('custom.permission'));
    }

    public function test_role_permissions_are_synced_correctly(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $role = Role::where('name', 'admin')->first();

        // Verificar se o admin tem todas as permissões
        $this->assertTrue($admin->can('products.view'));
        $this->assertTrue($admin->can('products.create'));
        $this->assertTrue($admin->can('users.view'));
        $this->assertTrue($admin->can('users.create'));
    }

    public function test_user_permissions_are_restricted(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        // Verificar se o usuário tem apenas permissões básicas
        $this->assertTrue($user->can('products.view'));
        $this->assertFalse($user->can('products.create'));
        $this->assertFalse($user->can('users.view'));
        $this->assertFalse($user->can('users.create'));
    }
}
