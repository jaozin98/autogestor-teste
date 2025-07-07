<?php

namespace App\Contracts\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    /**
     * Get all users with pagination and caching
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
     * Create a new user with validation and logging
     */
    public function createUser(array $data): User;

    /**
     * Update an existing user with validation and logging
     */
    public function updateUser(User $user, array $data): bool;

    /**
     * Update user profile (with profile-specific validation)
     */
    public function updateUserProfile(User $user, array $data): bool;

    /**
     * Delete a user with validation and logging
     */
    public function deleteUser(User $user): bool;

    /**
     * Delete user profile (self-deletion allowed)
     */
    public function deleteUserProfile(User $user): bool;

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
     * Assign role to user
     */
    public function assignRoleToUser(User $user, string $role): bool;

    /**
     * Remove role from user
     */
    public function removeRoleFromUser(User $user, string $role): bool;

    /**
     * Bulk update users
     */
    public function bulkUpdateUsers(array $userIds, array $data): int;

    /**
     * Bulk delete users
     */
    public function bulkDeleteUsers(array $userIds): int;

    /**
     * Validate user data
     */
    public function validateUserData(array $data, ?User $user = null): array;

    /**
     * Change user password
     */
    public function changeUserPassword(User $user, string $newPassword): bool;

    /**
     * Reset user password
     */
    public function resetUserPassword(User $user): string;
}
