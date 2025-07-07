<?php

namespace App\Services;

use App\Contracts\Repositories\BrandRepositoryInterface;
use App\Contracts\Services\BrandServiceInterface;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;

class BrandService implements BrandServiceInterface
{
    public function __construct(
        private BrandRepositoryInterface $brandRepository
    ) {}

    /**
     * Get all brands with optional pagination
     */
    public function getAllBrands(int $perPage = 15): LengthAwarePaginator
    {
        try {
            return $this->brandRepository->getAllBrands($perPage);
        } catch (Exception $e) {
            Log::error('Erro ao buscar marcas', [
                'error' => $e->getMessage(),
                'per_page' => $perPage,
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Get all brands for select dropdowns
     */
    public function getBrandsForSelect(): Collection
    {
        try {
            return $this->brandRepository->getBrandsForSelect();
        } catch (Exception $e) {
            Log::error('Erro ao buscar marcas para select', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Find brand by ID with relationships
     */
    public function findBrand(int $id): ?Brand
    {
        try {
            return $this->brandRepository->findBrand($id);
        } catch (Exception $e) {
            Log::error('Erro ao buscar marca por ID', [
                'brand_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Create a new brand
     */
    public function createBrand(array $data): Brand
    {
        try {
            DB::beginTransaction();

            $brand = $this->brandRepository->createBrand($data);

            Log::info('Marca criada com sucesso', [
                'brand_id' => $brand->id,
                'name' => $brand->name,
                'country_of_origin' => $brand->country_of_origin,
                'user_id' => Auth::id() ?? null,
            ]);

            DB::commit();
            return $brand;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar marca', [
                'data' => $data,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Update an existing brand
     */
    public function updateBrand(Brand $brand, array $data): bool
    {
        try {
            DB::beginTransaction();

            $oldValues = $brand->only(['name', 'country_of_origin', 'description']);
            $updated = $this->brandRepository->updateBrand($brand, $data);

            if ($updated) {
                Log::info('Marca atualizada com sucesso', [
                    'brand_id' => $brand->id,
                    'old_values' => $oldValues,
                    'new_values' => $data,
                    'user_id' => Auth::id() ?? null,
                ]);
            }

            DB::commit();
            return $updated;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar marca', [
                'brand_id' => $brand->id,
                'data' => $data,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Delete a brand
     */
    public function deleteBrand(Brand $brand): bool
    {
        try {
            DB::beginTransaction();

            $brandName = $brand->name;
            $brandId = $brand->id;

            // Check if brand has products
            if ($brand->products()->exists()) {
                throw new Exception("Não é possível excluir a marca '{$brandName}' pois ela possui produtos associados.");
            }

            $deleted = $this->brandRepository->deleteBrand($brand);

            if ($deleted) {
                Log::info('Marca excluída com sucesso', [
                    'brand_id' => $brandId,
                    'name' => $brandName,
                    'user_id' => Auth::id() ?? null,
                ]);
            }

            DB::commit();
            return $deleted;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao excluir marca', [
                'brand_id' => $brand->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Search brands by name or country
     */
    public function searchBrands(string $search, int $perPage = 15): LengthAwarePaginator
    {
        try {
            return $this->brandRepository->searchBrands($search, $perPage);
        } catch (Exception $e) {
            Log::error('Erro ao buscar marcas', [
                'search' => $search,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Get brands statistics
     */
    public function getBrandStats(): array
    {
        try {
            return [
                'total' => Brand::count(),
                'with_products' => Brand::has('products')->count(),
                'without_products' => Brand::doesntHave('products')->count(),
                'recent' => Brand::where('created_at', '>=', now()->subDays(30))->count(),
            ];
        } catch (Exception $e) {
            Log::error('Erro ao buscar estatísticas das marcas', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Get brands by country
     */
    public function getBrandsByCountry(string $country, int $perPage = 15): LengthAwarePaginator
    {
        try {
            return $this->brandRepository->getBrandsByCountry($country, $perPage);
        } catch (Exception $e) {
            Log::error('Erro ao buscar marcas por país', [
                'country' => $country,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Get brands founded between years
     */
    public function getBrandsFoundedBetween(int $startYear, int $endYear, int $perPage = 15): LengthAwarePaginator
    {
        try {
            return $this->brandRepository->getBrandsFoundedBetween($startYear, $endYear, $perPage);
        } catch (Exception $e) {
            Log::error('Erro ao buscar marcas por período de fundação', [
                'start_year' => $startYear,
                'end_year' => $endYear,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Get top brands by product count
     */
    public function getTopBrands(int $limit = 10): Collection
    {
        try {
            return $this->brandRepository->getTopBrands($limit);
        } catch (Exception $e) {
            Log::error('Erro ao buscar top marcas', [
                'limit' => $limit,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Get recently added brands
     */
    public function getRecentlyAddedBrands(int $limit = 10): Collection
    {
        try {
            return $this->brandRepository->getRecentlyAddedBrands($limit);
        } catch (Exception $e) {
            Log::error('Erro ao buscar marcas recentes', [
                'limit' => $limit,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Toggle brand active status
     */
    public function toggleBrandStatus(Brand $brand): bool
    {
        try {
            DB::beginTransaction();

            $oldStatus = $brand->is_active;
            $brand->is_active = !$brand->is_active;
            $brand->save();

            Log::info('Status da marca alterado com sucesso', [
                'brand_id' => $brand->id,
                'name' => $brand->name,
                'old_status' => $oldStatus,
                'new_status' => $brand->is_active,
                'user_id' => Auth::id() ?? null,
            ]);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao alterar status da marca', [
                'brand_id' => $brand->id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }
}
