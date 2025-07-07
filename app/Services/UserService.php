<?php

namespace App\Services;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Services\UserServiceInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Exception;

class UserService implements UserServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    /**
     * Get all users with pagination and caching
     */
    public function getAllUsers(int $perPage = 15): LengthAwarePaginator
    {
        try {
            return $this->userRepository->getAllUsers($perPage);
        } catch (Exception $e) {
            Log::error('Erro ao buscar usuários', [
                'error' => $e->getMessage(),
                'per_page' => $perPage,
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Find user by ID with relationships
     */
    public function findUser(int $id): ?User
    {
        try {
            return $this->userRepository->findUser($id);
        } catch (Exception $e) {
            Log::error('Erro ao buscar usuário por ID', [
                'user_id' => $id,
                'error' => $e->getMessage(),
                'current_user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Find user by email
     */
    public function findUserByEmail(string $email): ?User
    {
        try {
            return $this->userRepository->findUserByEmail($email);
        } catch (Exception $e) {
            Log::error('Erro ao buscar usuário por email', [
                'email' => $email,
                'error' => $e->getMessage(),
                'current_user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Create a new user with validation and logging
     */
    public function createUser(array $data): User
    {
        try {
            DB::beginTransaction();

            // Validate data
            $validatedData = $this->validateUserData($data);

            // Hash password if provided
            if (isset($validatedData['password'])) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            }

            // Handle email verification based on is_active field
            if (isset($data['is_active'])) {
                $validatedData['email_verified_at'] = $data['is_active'] ? now() : null;
            } else {
                // Set default values
                $validatedData['email_verified_at'] = $validatedData['email_verified_at'] ?? null;
            }

            // Create user
            $user = $this->userRepository->createUser($validatedData);

            Log::info('Usuário criado com sucesso', [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_by' => Auth::id() ?? null,
            ]);

            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar usuário', [
                'data' => $data,
                'error' => $e->getMessage(),
                'created_by' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Update an existing user with validation and logging
     */
    public function updateUser(User $user, array $data): bool
    {
        try {
            DB::beginTransaction();

            // Validate data
            $validatedData = $this->validateUserData($data, $user);

            // Hash password if provided
            if (isset($validatedData['password'])) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            }

            // Store old values for logging
            $oldValues = $user->only(['name', 'email']);

            // Update user
            $updated = $this->userRepository->updateUser($user, $validatedData);

            if ($updated) {
                Log::info('Usuário atualizado com sucesso', [
                    'user_id' => $user->id,
                    'old_values' => $oldValues,
                    'new_values' => $validatedData,
                    'updated_by' => Auth::id() ?? null,
                ]);
            }

            DB::commit();
            return $updated;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar usuário', [
                'user_id' => $user->id,
                'data' => $data,
                'error' => $e->getMessage(),
                'updated_by' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Update user profile (with profile-specific validation)
     */
    public function updateUserProfile(User $user, array $data): bool
    {
        try {
            DB::beginTransaction();

            // Validate profile data
            $validatedData = $this->validateProfileData($data, $user);

            // Hash password if provided
            if (isset($validatedData['password'])) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            }

            // Store old values for logging
            $oldValues = $user->only(['name', 'email']);

            // Update user
            $updated = $this->userRepository->updateUser($user, $validatedData);

            if ($updated) {
                Log::info('Perfil de usuário atualizado com sucesso', [
                    'user_id' => $user->id,
                    'old_values' => $oldValues,
                    'new_values' => $validatedData,
                    'self_updated' => true,
                ]);
            }

            DB::commit();
            return $updated;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar perfil de usuário', [
                'user_id' => $user->id,
                'data' => $data,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Delete a user with validation and logging
     */
    public function deleteUser(User $user): bool
    {
        try {
            DB::beginTransaction();

            // Prevent self-deletion
            if ($user->id === Auth::id()) {
                throw new Exception('Não é possível excluir o próprio usuário.');
            }

            $userName = $user->name;
            $deleted = $this->userRepository->deleteUser($user);

            if ($deleted) {
                Log::info('Usuário excluído com sucesso', [
                    'user_id' => $user->id,
                    'user_name' => $userName,
                    'deleted_by' => Auth::id() ?? null,
                ]);
            }

            DB::commit();
            return $deleted;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao excluir usuário', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'deleted_by' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Delete user profile (self-deletion allowed)
     */
    public function deleteUserProfile(User $user): bool
    {
        try {
            DB::beginTransaction();

            $userName = $user->name;
            $deleted = $this->userRepository->deleteUser($user);

            if ($deleted) {
                Log::info('Perfil de usuário excluído com sucesso', [
                    'user_id' => $user->id,
                    'user_name' => $userName,
                    'self_deleted' => true,
                ]);
            }

            DB::commit();
            return $deleted;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao excluir perfil de usuário', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Search users by name or email
     */
    public function searchUsers(string $search, int $perPage = 15): LengthAwarePaginator
    {
        try {
            return $this->userRepository->searchUsers($search, $perPage);
        } catch (Exception $e) {
            Log::error('Erro ao buscar usuários', [
                'search' => $search,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Get users by role
     */
    public function getUsersByRole(string $role, int $perPage = 15): LengthAwarePaginator
    {
        try {
            return $this->userRepository->getUsersByRole($role, $perPage);
        } catch (Exception $e) {
            Log::error('Erro ao buscar usuários por role', [
                'role' => $role,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Get active users
     */
    public function getActiveUsers(int $perPage = 15): LengthAwarePaginator
    {
        try {
            return $this->userRepository->getActiveUsers($perPage);
        } catch (Exception $e) {
            Log::error('Erro ao buscar usuários ativos', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Get users statistics
     */
    public function getUserStats(): array
    {
        try {
            return $this->userRepository->getUserStats();
        } catch (Exception $e) {
            Log::error('Erro ao buscar estatísticas de usuários', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Get recently registered users
     */
    public function getRecentlyRegisteredUsers(int $limit = 10): Collection
    {
        try {
            return $this->userRepository->getRecentlyRegisteredUsers($limit);
        } catch (Exception $e) {
            Log::error('Erro ao buscar usuários recentes', [
                'limit' => $limit,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Toggle user active status
     */
    public function toggleUserStatus(User $user): bool
    {
        try {
            $toggled = $this->userRepository->toggleUserStatus($user);

            if ($toggled) {
                $status = $user->email_verified_at ? 'ativado' : 'desativado';
                Log::info('Status do usuário alterado', [
                    'user_id' => $user->id,
                    'status' => $status,
                    'changed_by' => Auth::id() ?? null,
                ]);
            }

            return $toggled;
        } catch (Exception $e) {
            Log::error('Erro ao alterar status do usuário', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'changed_by' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Assign role to user
     */
    public function assignRoleToUser(User $user, string $role): bool
    {
        try {
            $roleModel = Role::where('name', $role)->first();

            if (!$roleModel) {
                throw new Exception("Role '{$role}' não encontrada.");
            }

            $user->assignRole($role);

            Log::info('Role atribuída ao usuário', [
                'user_id' => $user->id,
                'role' => $role,
                'assigned_by' => Auth::id() ?? null,
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Erro ao atribuir role ao usuário', [
                'user_id' => $user->id,
                'role' => $role,
                'error' => $e->getMessage(),
                'assigned_by' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Remove role from user
     */
    public function removeRoleFromUser(User $user, string $role): bool
    {
        try {
            $user->removeRole($role);

            Log::info('Role removida do usuário', [
                'user_id' => $user->id,
                'role' => $role,
                'removed_by' => Auth::id() ?? null,
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Erro ao remover role do usuário', [
                'user_id' => $user->id,
                'role' => $role,
                'error' => $e->getMessage(),
                'removed_by' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Bulk update users
     */
    public function bulkUpdateUsers(array $userIds, array $data): int
    {
        try {
            $updated = $this->userRepository->bulkUpdateUsers($userIds, $data);

            Log::info('Usuários atualizados em lote', [
                'user_ids' => $userIds,
                'updated_count' => $updated,
                'updated_by' => Auth::id() ?? null,
            ]);

            return $updated;
        } catch (Exception $e) {
            Log::error('Erro ao atualizar usuários em lote', [
                'user_ids' => $userIds,
                'error' => $e->getMessage(),
                'updated_by' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Bulk delete users
     */
    public function bulkDeleteUsers(array $userIds): int
    {
        try {
            // Prevent self-deletion
            $currentUserId = Auth::id();
            if ($currentUserId && in_array($currentUserId, $userIds)) {
                throw new Exception('Não é possível excluir o próprio usuário.');
            }

            $deleted = $this->userRepository->bulkDeleteUsers($userIds);

            Log::info('Usuários excluídos em lote', [
                'user_ids' => $userIds,
                'deleted_count' => $deleted,
                'deleted_by' => $currentUserId ?? null,
            ]);

            return $deleted;
        } catch (Exception $e) {
            Log::error('Erro ao excluir usuários em lote', [
                'user_ids' => $userIds,
                'error' => $e->getMessage(),
                'deleted_by' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Validate user data
     */
    public function validateUserData(array $data, ?User $user = null): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email' . ($user ? ",{$user->id}" : ''),
        ];

        if (!$user || isset($data['password'])) {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        }

        return $validator->validated();
    }

    /**
     * Validate profile data (email is optional)
     */
    public function validateProfileData(array $data, User $user): array
    {
        $rules = [
            'name' => 'required|string|max:255',
        ];

        // Email is optional for profile updates
        if (isset($data['email'])) {
            $rules['email'] = 'required|string|email|max:255|unique:users,email,' . $user->id;
        }

        // Password is optional for profile updates
        if (isset($data['password'])) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        }

        return $validator->validated();
    }

    /**
     * Change user password
     */
    public function changeUserPassword(User $user, string $newPassword): bool
    {
        try {
            $user->password = Hash::make($newPassword);
            $updated = $user->save();

            if ($updated) {
                Log::info('Senha do usuário alterada', [
                    'user_id' => $user->id,
                    'changed_by' => Auth::id() ?? null,
                ]);
            }

            return $updated;
        } catch (Exception $e) {
            Log::error('Erro ao alterar senha do usuário', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'changed_by' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Reset user password
     */
    public function resetUserPassword(User $user): string
    {
        try {
            $newPassword = Str::random(12);
            $this->changeUserPassword($user, $newPassword);

            Log::info('Senha do usuário redefinida', [
                'user_id' => $user->id,
                'reset_by' => Auth::id() ?? null,
            ]);

            return $newPassword;
        } catch (Exception $e) {
            Log::error('Erro ao redefinir senha do usuário', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'reset_by' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }
}
