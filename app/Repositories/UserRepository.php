<?php

namespace App\Repositories;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Get all users with pagination
     */
    public function getAllUsers(int $perPage = 15): LengthAwarePaginator
    {
        return User::with('roles')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Find user by ID with relationships
     */
    public function findUser(int $id): ?User
    {
        return User::with('roles')->find($id);
    }

    /**
     * Find user by email
     */
    public function findUserByEmail(string $email): ?User
    {
        return User::with('roles')->where('email', $email)->first();
    }

    /**
     * Create a new user
     */
    public function createUser(array $data): User
    {
        return User::create($data);
    }

    /**
     * Update an existing user
     */
    public function updateUser(User $user, array $data): bool
    {
        return $user->update($data);
    }

    /**
     * Delete a user
     */
    public function deleteUser(User $user): bool
    {
        return $user->delete();
    }

    /**
     * Search users by name or email
     */
    public function searchUsers(string $search, int $perPage = 15): LengthAwarePaginator
    {
        return User::with('roles')
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get users by role
     */
    public function getUsersByRole(string $role, int $perPage = 15): LengthAwarePaginator
    {
        return User::with('roles')
            ->whereHas('roles', function ($query) use ($role) {
                $query->where('name', $role);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get active users
     */
    public function getActiveUsers(int $perPage = 15): LengthAwarePaginator
    {
        return User::with('roles')
            ->whereNotNull('email_verified_at')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get users statistics
     */
    public function getUserStats(): array
    {
        return [
            'total' => User::count(),
            'active' => User::whereNotNull('email_verified_at')->count(),
            'inactive' => User::whereNull('email_verified_at')->count(),
            'admin' => User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->count(),
            'user' => User::whereHas('roles', function ($query) {
                $query->where('name', 'user');
            })->count(),
            'recent' => User::where('created_at', '>=', now()->subDays(30))->count(),
        ];
    }

    /**
     * Get recently registered users
     */
    public function getRecentlyRegisteredUsers(int $limit = 10): Collection
    {
        return User::with('roles')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Toggle user active status
     */
    public function toggleUserStatus(User $user): bool
    {
        $user->email_verified_at = $user->email_verified_at ? null : now();
        return $user->save();
    }

    /**
     * Bulk update users
     */
    public function bulkUpdateUsers(array $userIds, array $data): int
    {
        return User::whereIn('id', $userIds)->update($data);
    }

    /**
     * Bulk delete users
     */
    public function bulkDeleteUsers(array $userIds): int
    {
        return User::whereIn('id', $userIds)->delete();
    }
}
