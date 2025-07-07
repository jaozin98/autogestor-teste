<?php

namespace Tests\Feature\Console;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ClearPermissionCacheCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Criar algumas permissões e roles para testar o cache
        $permission = Permission::create(['name' => 'test-permission']);
        $role = Role::create(['name' => 'test-role']);
        $role->givePermissionTo($permission);
    }

    /** @test */
    public function it_clears_permission_cache_successfully()
    {
        $this->artisan('permissions:clear-cache')
            ->expectsOutput('Limpando cache de permissões...')
            ->expectsOutput('Cache de permissões limpo com sucesso!')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_handles_permission_registrar_exception()
    {
        // Mock do PermissionRegistrar para simular erro
        $mockRegistrar = $this->createMock(\Spatie\Permission\PermissionRegistrar::class);
        $mockRegistrar->method('forgetCachedPermissions')
            ->willThrowException(new \Exception('Cache error'));

        app()->instance(\Spatie\Permission\PermissionRegistrar::class, $mockRegistrar);

        $this->artisan('permissions:clear-cache')
            ->expectsOutput('Limpando cache de permissões...')
            ->expectsOutput('Erro ao limpar cache de permissões: Cache error')
            ->assertExitCode(1);
    }

    /** @test */
    public function it_works_with_empty_permission_cache()
    {
        // Limpar todas as permissões e roles de forma segura
        // Primeiro remover as relações
        DB::table('model_has_roles')->delete();
        DB::table('model_has_permissions')->delete();
        DB::table('role_has_permissions')->delete();

        // Depois remover as entidades principais
        Role::query()->delete();
        Permission::query()->delete();

        $this->artisan('permissions:clear-cache')
            ->expectsOutput('Limpando cache de permissões...')
            ->expectsOutput('Cache de permissões limpo com sucesso!')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_clears_cache_with_complex_permission_structure()
    {
        // Criar estrutura complexa de permissões
        $permissions = [
            'user.create',
            'user.read',
            'user.update',
            'user.delete',
            'product.create',
            'product.read',
            'product.update',
            'product.delete'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $roles = [
            'admin' => $permissions,
            'manager' => ['user.read', 'product.read', 'product.update'],
            'user' => ['product.read']
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::create(['name' => $roleName]);
            $role->givePermissionTo($rolePermissions);
        }

        $this->artisan('permissions:clear-cache')
            ->expectsOutput('Limpando cache de permissões...')
            ->expectsOutput('Cache de permissões limpo com sucesso!')
            ->assertExitCode(0);
    }
}
