<?php

namespace App\Services;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Services\CategoryServiceInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;

class CategoryService implements CategoryServiceInterface
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {}
    /**
     * Get all categories with optional pagination
     */
    public function getAllCategories(int $perPage = 15): LengthAwarePaginator
    {
        try {
            return $this->categoryRepository->getAllCategories($perPage);
        } catch (Exception $e) {
            Log::error('Erro ao buscar categorias', [
                'error' => $e->getMessage(),
                'per_page' => $perPage,
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Get all active categories for select dropdowns
     */
    public function getActiveCategoriesForSelect(): Collection
    {
        try {
            return $this->categoryRepository->getActiveCategoriesForSelect();
        } catch (Exception $e) {
            Log::error('Erro ao buscar categorias ativas para select', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Find category by ID with relationships
     */
    public function findCategory(int $id): ?Category
    {
        try {
            return $this->categoryRepository->findCategory($id);
        } catch (Exception $e) {
            Log::error('Erro ao buscar categoria por ID', [
                'category_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Create a new category
     */
    public function createCategory(array $data): Category
    {
        try {
            DB::beginTransaction();

            $category = $this->categoryRepository->createCategory($data);

            Log::info('Categoria criada com sucesso', [
                'category_id' => $category->id,
                'name' => $category->name,
                'user_id' => Auth::id() ?? null,
            ]);

            DB::commit();
            return $category;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar categoria', [
                'data' => $data,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Update an existing category
     */
    public function updateCategory(Category $category, array $data): bool
    {
        try {
            DB::beginTransaction();

            $oldValues = $category->only(['name', 'description', 'is_active']);
            $updated = $this->categoryRepository->updateCategory($category, $data);

            if ($updated) {
                Log::info('Categoria atualizada com sucesso', [
                    'category_id' => $category->id,
                    'old_values' => $oldValues,
                    'new_values' => $data,
                    'user_id' => Auth::id() ?? null,
                ]);
            }

            DB::commit();
            return $updated;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar categoria', [
                'category_id' => $category->id,
                'data' => $data,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Delete a category
     */
    public function deleteCategory(Category $category): bool
    {
        try {
            DB::beginTransaction();

            $categoryName = $category->name;
            $categoryId = $category->id;

            // Check if category has products
            if ($category->products()->exists()) {
                throw new Exception("Não é possível excluir a categoria '{$categoryName}' pois ela possui produtos associados.");
            }

            $deleted = $this->categoryRepository->deleteCategory($category);

            if ($deleted) {
                Log::info('Categoria excluída com sucesso', [
                    'category_id' => $categoryId,
                    'name' => $categoryName,
                    'user_id' => Auth::id() ?? null,
                ]);
            }

            DB::commit();
            return $deleted;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao excluir categoria', [
                'category_id' => $category->id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Search categories by name or description
     */
    public function searchCategories(string $search, int $perPage = 15): LengthAwarePaginator
    {
        try {
            return $this->categoryRepository->searchCategories($search, $perPage);
        } catch (Exception $e) {
            Log::error('Erro ao buscar categorias', [
                'search' => $search,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Get categories statistics
     */
    public function getCategoryStats(): array
    {
        try {
            return [
                'total' => Category::count(),
                'active' => Category::where('is_active', true)->count(),
                'inactive' => Category::where('is_active', false)->count(),
                'with_products' => Category::has('products')->count(),
                'without_products' => Category::doesntHave('products')->count(),
            ];
        } catch (Exception $e) {
            Log::error('Erro ao buscar estatísticas das categorias', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Toggle category active status
     */
    public function toggleCategoryStatus(Category $category): bool
    {
        try {
            DB::beginTransaction();

            $updated = $category->toggleActive();

            if ($updated) {
                Log::info('Status da categoria alterado com sucesso', [
                    'category_id' => $category->id,
                    'name' => $category->name,
                    'new_status' => $category->is_active ? 'ativo' : 'inativo',
                    'user_id' => Auth::id() ?? null,
                ]);
            }

            DB::commit();
            return $updated;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao alterar status da categoria', [
                'category_id' => $category->id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Get categories with most products
     */
    public function getTopCategories(int $limit = 5): Collection
    {
        try {
            return $this->categoryRepository->getTopCategories($limit);
        } catch (Exception $e) {
            Log::error('Erro ao buscar top categorias', [
                'limit' => $limit,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Get recently added categories
     */
    public function getRecentlyAddedCategories(int $limit = 10): Collection
    {
        try {
            return $this->categoryRepository->getRecentlyAddedCategories($limit);
        } catch (Exception $e) {
            Log::error('Erro ao buscar categorias recentes', [
                'limit' => $limit,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Get active categories with product count
     */
    public function getActiveCategoriesWithProductCount(): Collection
    {
        try {
            return $this->categoryRepository->getActiveCategoriesWithProductCount();
        } catch (Exception $e) {
            Log::error('Erro ao buscar categorias ativas com contagem de produtos', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }
}
