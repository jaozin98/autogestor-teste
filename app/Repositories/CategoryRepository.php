<?php

namespace App\Repositories;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * Get all categories with optional pagination
     */
    public function getAllCategories(int $perPage = 15): LengthAwarePaginator
    {
        $cacheKey = "categories.all.{$perPage}." . request()->get('page', 1);

        return Cache::remember($cacheKey, 300, function () use ($perPage) {
            return Category::withCount('products')
                ->orderBy('name')
                ->paginate($perPage);
        });
    }

    /**
     * Get all active categories for select dropdowns
     */
    public function getActiveCategoriesForSelect(): Collection
    {
        return Cache::remember('categories.active.select', 300, function () {
            return Category::active()
                ->select('id', 'name')
                ->orderBy('name')
                ->get();
        });
    }

    /**
     * Find category by ID with relationships
     */
    public function findCategory(int $id): ?Category
    {
        return Category::with(['products' => function ($query) {
            $query->with('brand');
        }])->withCount('products')->find($id);
    }

    /**
     * Find category by name
     */
    public function findCategoryByName(string $name): ?Category
    {
        return Category::where('name', $name)->first();
    }

    /**
     * Create a new category
     */
    public function createCategory(array $data): Category
    {
        return Category::create($data);
    }

    /**
     * Update an existing category
     */
    public function updateCategory(Category $category, array $data): bool
    {
        return $category->update($data);
    }

    /**
     * Delete a category
     */
    public function deleteCategory(Category $category): bool
    {
        return $category->delete();
    }

    /**
     * Search categories by name or description
     */
    public function searchCategories(string $search, int $perPage = 15): LengthAwarePaginator
    {
        return Category::search($search)
            ->withCount('products')
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Get active categories
     */
    public function getActiveCategories(int $perPage = 15): LengthAwarePaginator
    {
        return Category::active()
            ->withCount('products')
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Get inactive categories
     */
    public function getInactiveCategories(int $perPage = 15): LengthAwarePaginator
    {
        return Category::inactive()
            ->withCount('products')
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Get categories with products
     */
    public function getCategoriesWithProducts(int $perPage = 15): LengthAwarePaginator
    {
        return Category::withProducts()
            ->withCount('products')
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Get categories without products
     */
    public function getCategoriesWithoutProducts(int $perPage = 15): LengthAwarePaginator
    {
        return Category::withoutProducts()
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Get top categories by product count
     */
    public function getTopCategories(int $limit = 5): Collection
    {
        return Category::withCount('products')
            ->orderByDesc('products_count')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recently added categories
     */
    public function getRecentlyAddedCategories(int $limit = 10): Collection
    {
        return Category::orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get active categories with product count
     */
    public function getActiveCategoriesWithProductCount(): Collection
    {
        return Category::active()
            ->withCount('products')
            ->orderBy('name')
            ->get();
    }
}
