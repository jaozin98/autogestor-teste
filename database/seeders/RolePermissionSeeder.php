<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Execute the database seeds.
     */
    public function run(): void
    {
        // Clear existing roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Product Permissions
        $productPermissions = [
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',
            'products.export',
            'products.import',
            'products.bulk_actions'
        ];

        // Create Category Permissions
        $categoryPermissions = [
            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',
            'categories.manage_hierarchy'
        ];

        // Create Brand Permissions
        $brandPermissions = [
            'brands.view',
            'brands.create',
            'brands.edit',
            'brands.delete',
            'brands.manage_logos'
        ];

        // Create User Management Permissions
        $userPermissions = [
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.assign_roles',
            'users.manage_permissions'
        ];

        // Create System Permissions
        $systemPermissions = [
            'system.settings',
            'system.backup',
            'system.logs',
            'system.maintenance'
        ];

        // Create Report Permissions
        $reportPermissions = [
            'reports.view',
            'reports.export',
            'reports.analytics',
            'reports.dashboard'
        ];

        // Create all permissions (only if they don't exist)
        $allPermissions = array_merge(
            $productPermissions,
            $categoryPermissions,
            $brandPermissions,
            $userPermissions,
            $systemPermissions,
            $reportPermissions
        );

        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Buscar todas as permissões do banco
        $allPermissions = \Spatie\Permission\Models\Permission::pluck('name')->toArray();

        // Admin - Todas as permissões
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions($allPermissions);

        // User - Permissões básicas para CRUD de produtos, categorias e marcas
        $user = Role::firstOrCreate(['name' => 'user']);
        $userPermissions = [
            // Produtos - permissões básicas
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',
            // Categorias - permissões básicas
            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',
            // Marcas - permissões básicas
            'brands.view',
            'brands.create',
            'brands.edit',
            'brands.delete',
            // Relatórios básicos
            'reports.view',
            'reports.dashboard'
        ];
        $user->syncPermissions($userPermissions);

        // Assign roles to default users
        $this->assignDefaultRoles();

        // Limpar cache de permissões
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /**
     * Assign default roles to existing users
     */
    private function assignDefaultRoles(): void
    {
        // Assign admin role to admin user
        $adminUser = User::where('email', 'admin@autogestor.com')->first();
        if ($adminUser) {
            $adminUser->assignRole('admin');
        }

        // Assign admin role to any user with admin in email
        $adminUsers = User::where('email', 'like', '%admin%')->get();
        foreach ($adminUsers as $user) {
            if (!$user->hasRole('admin')) {
                $user->assignRole('admin');
            }
        }

        // Assign user role to remaining users
        $remainingUsers = User::whereDoesntHave('roles')->get();
        foreach ($remainingUsers as $user) {
            $user->assignRole('user');
        }
    }
}
