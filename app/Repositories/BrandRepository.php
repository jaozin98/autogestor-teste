<?php

namespace App\Repositories;

use App\Contracts\Repositories\BrandRepositoryInterface;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class BrandRepository implements BrandRepositoryInterface
{
    /**
     * Get all brands with optional pagination
     */
    public function getAllBrands(int $perPage = 15): LengthAwarePaginator
    {
        $cacheKey = "brands.all.{$perPage}." . request()->get('page', 1);

        return Cache::remember($cacheKey, 300, function () use ($perPage) {
            return Brand::with('products')
                ->orderBy('name')
                ->paginate($perPage);
        });
    }

    /**
     * Get all brands for select dropdowns
     */
    public function getBrandsForSelect(): Collection
    {
        return Cache::remember('brands.select', 300, function () {
            return Brand::select('id', 'name')
                ->orderBy('name')
                ->get();
        });
    }

    /**
     * Find brand by ID with relationships
     */
    public function findBrand(int $id): ?Brand
    {
        return Brand::with('products')->find($id);
    }

    /**
     * Find brand by name
     */
    public function findBrandByName(string $name): ?Brand
    {
        return Brand::where('name', $name)->first();
    }

    /**
     * Create a new brand
     */
    public function createBrand(array $data): Brand
    {
        return Brand::create($data);
    }

    /**
     * Update an existing brand
     */
    public function updateBrand(Brand $brand, array $data): bool
    {
        return $brand->update($data);
    }

    /**
     * Delete a brand
     */
    public function deleteBrand(Brand $brand): bool
    {
        return $brand->delete();
    }

    /**
     * Search brands by name or country
     */
    public function searchBrands(string $search, int $perPage = 15): LengthAwarePaginator
    {
        return Brand::search($search)
            ->with('products')
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Get brands by country
     */
    public function getBrandsByCountry(string $country, int $perPage = 15): LengthAwarePaginator
    {
        return Brand::byCountry($country)
            ->with('products')
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Get brands founded between years
     */
    public function getBrandsFoundedBetween(int $startYear, int $endYear, int $perPage = 15): LengthAwarePaginator
    {
        return Brand::foundedBetween($startYear, $endYear)
            ->with('products')
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Get brands with products
     */
    public function getBrandsWithProducts(int $perPage = 15): LengthAwarePaginator
    {
        return Brand::withProducts()
            ->with('products')
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Get brands without products
     */
    public function getBrandsWithoutProducts(int $perPage = 15): LengthAwarePaginator
    {
        return Brand::withoutProducts()
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Get top brands by product count
     */
    public function getTopBrands(int $limit = 10): Collection
    {
        return Brand::withCount('products')
            ->orderByDesc('products_count')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recently added brands
     */
    public function getRecentlyAddedBrands(int $limit = 10): Collection
    {
        return Brand::orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }
}
