<?php

namespace App\Contracts\Services;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BrandServiceInterface
{
    /**
     * Get all brands with optional pagination
     */
    public function getAllBrands(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get all brands for select dropdowns
     */
    public function getBrandsForSelect(): Collection;

    /**
     * Find brand by ID with relationships
     */
    public function findBrand(int $id): ?Brand;

    /**
     * Create a new brand
     */
    public function createBrand(array $data): Brand;

    /**
     * Update an existing brand
     */
    public function updateBrand(Brand $brand, array $data): bool;

    /**
     * Delete a brand
     */
    public function deleteBrand(Brand $brand): bool;

    /**
     * Search brands by name or country
     */
    public function searchBrands(string $search, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get brands statistics
     */
    public function getBrandStats(): array;

    /**
     * Get brands by country
     */
    public function getBrandsByCountry(string $country, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get brands founded between years
     */
    public function getBrandsFoundedBetween(int $startYear, int $endYear, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get top brands by product count
     */
    public function getTopBrands(int $limit = 10): Collection;

    /**
     * Get recently added brands
     */
    public function getRecentlyAddedBrands(int $limit = 10): Collection;

    /**
     * Toggle brand active status
     */
    public function toggleBrandStatus(Brand $brand): bool;
}
