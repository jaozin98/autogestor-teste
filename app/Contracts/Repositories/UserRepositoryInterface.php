<?php

namespace App\Contracts\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    /**
     * Get all users with pagination
     */
    public function getAllUsers(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find user by ID with relationships
     */
    public function findUser(int $id): ?User;

    /**
     * Find user by email
     */
    public function findUserByEmail(string $email): ?User;

    /**
     * Create a new user
     */
    public function createUser(array $data): User;

    /**
     * Update an existing user
     */
    public function updateUser(User $user, array $data): bool;

    /**
     * Delete a user
     */
    public function deleteUser(User $user): bool;

    /**
     * Search users by name or email
     */
    public function searchUsers(string $search, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get users by role
     */
    public function getUsersByRole(string $role, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get active users
     */
    public function getActiveUsers(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get users statistics
     */
    public function getUserStats(): array;

    /**
     * Get recently registered users
     */
    public function getRecentlyRegisteredUsers(int $limit = 10): Collection;

    /**
     * Toggle user active status
     */
    public function toggleUserStatus(User $user): bool;

    /**
     * Bulk update users
     */
    public function bulkUpdateUsers(array $userIds, array $data): int;

    /**
     * Bulk delete users
     */
    public function bulkDeleteUsers(array $userIds): int;
}
