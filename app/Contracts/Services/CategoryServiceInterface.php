<?php

namespace App\Contracts\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryServiceInterface
{
    /**
     * Get all categories with optional pagination
     */
    public function getAllCategories(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get all active categories for select dropdowns
     */
    public function getActiveCategoriesForSelect(): Collection;

    /**
     * Find category by ID with relationships
     */
    public function findCategory(int $id): ?Category;

    /**
     * Create a new category
     */
    public function createCategory(array $data): Category;

    /**
     * Update an existing category
     */
    public function updateCategory(Category $category, array $data): bool;

    /**
     * Delete a category
     */
    public function deleteCategory(Category $category): bool;

    /**
     * Search categories by name or description
     */
    public function searchCategories(string $search, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get categories statistics
     */
    public function getCategoryStats(): array;

    /**
     * Toggle category active status
     */
    public function toggleCategoryStatus(Category $category): bool;

    /**
     * Get top categories by product count
     */
    public function getTopCategories(int $limit = 5): Collection;

    /**
     * Get recently added categories
     */
    public function getRecentlyAddedCategories(int $limit = 10): Collection;

    /**
     * Get active categories with product count
     */
    public function getActiveCategoriesWithProductCount(): Collection;
}
