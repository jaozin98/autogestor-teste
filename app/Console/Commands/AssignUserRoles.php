<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class AssignUserRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:assign-roles {--role=user : Role padrão para usuários sem role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atribui roles aos usuários que não possuem nenhuma role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Verificando usuários sem roles...');

        $usersWithoutRoles = User::whereDoesntHave('roles')->get();

        if ($usersWithoutRoles->isEmpty()) {
            $this->info('Todos os usuários já possuem roles atribuídas.');
            return 0;
        }

        $this->info("Encontrados {$usersWithoutRoles->count()} usuários sem roles.");

        $defaultRole = $this->option('role');

        // Limpar cache de permissões antes de verificar se a role existe
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Verificar se a role existe
        if (!Role::where('name', $defaultRole)->exists()) {
            $this->error("Role '{$defaultRole}' não existe!");
            return 1;
        }

        $bar = $this->output->createProgressBar($usersWithoutRoles->count());
        $bar->start();

        foreach ($usersWithoutRoles as $user) {
            // Lógica para atribuir roles
            if (str_contains(strtolower($user->email), 'admin')) {
                $roleToAssign = 'admin';
            } else {
                $roleToAssign = $defaultRole;
            }

            $user->assignRole($roleToAssign);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Roles atribuídas com sucesso!');

        // Mostrar resumo
        $this->newLine();
        $this->info('Resumo das roles atribuídas:');
        User::with('roles')->get()->each(function ($user) {
            $roles = $user->roles->pluck('name')->implode(', ');
            $this->line("- {$user->name} ({$user->email}): {$roles}");
        });

        return 0;
    }
}
