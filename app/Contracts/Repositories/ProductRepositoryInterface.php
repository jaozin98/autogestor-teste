<?php

namespace App\Contracts\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    /**
     * Get all products with pagination
     */
    public function getAllProducts(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get active products for select dropdowns
     */
    public function getActiveProductsForSelect(): Collection;

    /**
     * Find product by ID with relationships
     */
    public function findProduct(int $id): ?Product;

    /**
     * Find product by SKU
     */
    public function findProductBySku(string $sku): ?Product;

    /**
     * Find product by barcode
     */
    public function findProductByBarcode(string $barcode): ?Product;

    /**
     * Create a new product
     */
    public function createProduct(array $data): Product;

    /**
     * Update an existing product
     */
    public function updateProduct(Product $product, array $data): bool;

    /**
     * Delete a product
     */
    public function deleteProduct(Product $product): bool;

    /**
     * Search products by name, description, SKU or barcode
     */
    public function searchProducts(string $search, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get products by category
     */
    public function getProductsByCategory(int $categoryId, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get products by brand
     */
    public function getProductsByBrand(int $brandId, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get low stock products
     */
    public function getLowStockProducts(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get out of stock products
     */
    public function getOutOfStockProducts(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get products statistics
     */
    public function getProductStats(): array;

    /**
     * Update product stock
     */
    public function updateStock(Product $product, int $quantity, string $operation = 'add'): bool;

    /**
     * Get top selling products
     */
    public function getTopSellingProducts(int $limit = 10): Collection;

    /**
     * Get recently added products
     */
    public function getRecentlyAddedProducts(int $limit = 10): Collection;
}
