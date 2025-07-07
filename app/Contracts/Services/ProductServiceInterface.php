<?php

namespace App\Contracts\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductServiceInterface
{
    /**
     * Get all products with pagination and caching
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
     * Create a new product with validation and logging
     */
    public function createProduct(array $data): Product;

    /**
     * Update an existing product with validation and logging
     */
    public function updateProduct(Product $product, array $data): bool;

    /**
     * Delete a product with validation and logging
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
     * Update product stock with validation
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

    /**
     * Toggle product active status
     */
    public function toggleProductStatus(Product $product): bool;

    /**
     * Bulk update products
     */
    public function bulkUpdateProducts(array $productIds, array $data): int;

    /**
     * Bulk delete products
     */
    public function bulkDeleteProducts(array $productIds): int;

    /**
     * Import products from CSV
     */
    public function importProductsFromCsv(string $filePath): array;

    /**
     * Export products to CSV
     */
    public function exportProductsToCsv(array $filters = []): string;

    /**
     * Generate product SKU
     */
    public function generateSku(string $name, int $categoryId): string;

    /**
     * Validate product data
     */
    public function validateProductData(array $data, ?Product $product = null): array;
}
