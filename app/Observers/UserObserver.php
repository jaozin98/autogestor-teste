<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        Log::info('Usuário criado via Observer', [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);

        // Atribuir role padrão baseada no email
        $this->assignDefaultRole($user);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        Log::info('Usuário atualizado via Observer', [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'changes' => $user->getChanges(),
        ]);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        Log::info('Usuário excluído via Observer', [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        Log::info('Usuário restaurado via Observer', [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        Log::info('Usuário excluído permanentemente via Observer', [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    /**
     * Atribuir role padrão ao usuário baseado no email
     */
    private function assignDefaultRole(User $user): void
    {
        // Verificar se o usuário já tem roles
        if ($user->roles()->count() > 0) {
            Log::info('Usuário já possui roles atribuídas', [
                'user_id' => $user->id,
                'roles' => $user->roles->pluck('name')->toArray(),
            ]);
            return;
        }

        // Lógica para atribuir role baseada no email
        $roleToAssign = 'user'; // Role padrão

        // Se o email contém 'admin', atribuir role admin
        if (str_contains(strtolower($user->email), 'admin')) {
            $roleToAssign = 'admin';
        }

        // Verificar se a role existe
        $role = Role::where('name', $roleToAssign)->first();

        if (!$role) {
            Log::warning('Role não encontrada, criando role padrão', [
                'user_id' => $user->id,
                'role_name' => $roleToAssign,
            ]);

            // Criar role se não existir
            $role = Role::create(['name' => $roleToAssign]);
        }

        // Atribuir role ao usuário
        $user->assignRole($role);

        Log::info('Role atribuída automaticamente ao usuário', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'role_assigned' => $roleToAssign,
        ]);
    }
}
