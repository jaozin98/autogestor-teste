<?php

namespace App\Repositories;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProductRepository implements ProductRepositoryInterface
{
    private const CACHE_TTL = 3600; // 1 hour
    private const CACHE_PREFIX = 'products';

    /**
     * Get all products with pagination
     */
    public function getAllProducts(int $perPage = 15): LengthAwarePaginator
    {
        $cacheKey = "{$this->getCachePrefix()}.all.{$perPage}." . request()->get('page', 1);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($perPage) {
            return Product::with(['category', 'brand'])
                ->orderBy('name')
                ->paginate($perPage);
        });
    }

    /**
     * Get active products for select dropdowns
     */
    public function getActiveProductsForSelect(): Collection
    {
        $cacheKey = "{$this->getCachePrefix()}.active.select";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () {
            return Product::where('is_active', true)
                ->select('id', 'name', 'sku')
                ->orderBy('name')
                ->get();
        });
    }

    /**
     * Find product by ID with relationships
     */
    public function findProduct(int $id): ?Product
    {
        $cacheKey = "{$this->getCachePrefix()}.find.{$id}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($id) {
            return Product::with(['category', 'brand'])->find($id);
        });
    }

    /**
     * Find product by SKU
     */
    public function findProductBySku(string $sku): ?Product
    {
        return Product::where('sku', $sku)->first();
    }

    /**
     * Find product by barcode
     */
    public function findProductByBarcode(string $barcode): ?Product
    {
        return Product::where('barcode', $barcode)->first();
    }

    /**
     * Create a new product
     */
    public function createProduct(array $data): Product
    {
        $product = Product::create($data);
        $this->clearCache();
        return $product;
    }

    /**
     * Update an existing product
     */
    public function updateProduct(Product $product, array $data): bool
    {
        $updated = $product->update($data);
        if ($updated) {
            $this->clearCache();
        }
        return $updated;
    }

    /**
     * Delete a product
     */
    public function deleteProduct(Product $product): bool
    {
        $deleted = $product->delete();
        if ($deleted) {
            $this->clearCache();
        }
        return $deleted;
    }

    /**
     * Search products by name, description, SKU or barcode
     */
    public function searchProducts(string $search, int $perPage = 15): LengthAwarePaginator
    {
        return Product::with(['category', 'brand'])
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Get products by category
     */
    public function getProductsByCategory(int $categoryId, int $perPage = 15): LengthAwarePaginator
    {
        return Product::with(['category', 'brand'])
            ->where('category_id', $categoryId)
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Get products by brand
     */
    public function getProductsByBrand(int $brandId, int $perPage = 15): LengthAwarePaginator
    {
        return Product::with(['category', 'brand'])
            ->where('brand_id', $brandId)
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Get low stock products
     */
    public function getLowStockProducts(int $perPage = 15): LengthAwarePaginator
    {
        return Product::with(['category', 'brand'])
            ->whereRaw('stock <= min_stock')
            ->where('stock', '>', 0)
            ->orderBy('stock')
            ->paginate($perPage);
    }

    /**
     * Get out of stock products
     */
    public function getOutOfStockProducts(int $perPage = 15): LengthAwarePaginator
    {
        return Product::with(['category', 'brand'])
            ->where('stock', 0)
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Get products statistics
     */
    public function getProductStats(): array
    {
        $cacheKey = "{$this->getCachePrefix()}.stats";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () {
            return [
                'total' => Product::count(),
                'active' => Product::where('is_active', true)->count(),
                'inactive' => Product::where('is_active', false)->count(),
                'with_stock' => Product::where('stock', '>', 0)->count(),
                'out_of_stock' => Product::where('stock', 0)->count(),
                'low_stock' => Product::whereRaw('stock <= min_stock')->where('stock', '>', 0)->count(),
                'total_value' => Product::sum(DB::raw('price * stock')),
                'avg_price' => Product::avg('price'),
                'categories_count' => Product::distinct('category_id')->count(),
                'brands_count' => Product::distinct('brand_id')->count(),
            ];
        });
    }

    /**
     * Update product stock
     */
    public function updateStock(Product $product, int $quantity, string $operation = 'add'): bool
    {
        $currentStock = $product->stock;

        if ($operation === 'add') {
            $newStock = $currentStock + $quantity;
        } elseif ($operation === 'subtract') {
            $newStock = max(0, $currentStock - $quantity);
        } else {
            $newStock = $quantity;
        }

        $updated = $product->update(['stock' => $newStock]);

        if ($updated) {
            $this->clearCache();
        }

        return $updated;
    }

    /**
     * Get top selling products
     */
    public function getTopSellingProducts(int $limit = 10): Collection
    {
        // This would typically join with sales table
        // For now, returning recently updated products
        return Product::with(['category', 'brand'])
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recently added products
     */
    public function getRecentlyAddedProducts(int $limit = 10): Collection
    {
        return Product::with(['category', 'brand'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Clear all product cache
     */
    private function clearCache(): void
    {
        Cache::forget("{$this->getCachePrefix()}.stats");
        Cache::forget("{$this->getCachePrefix()}.active.select");

        // Clear paginated cache (this is a simplified approach)
        for ($i = 1; $i <= 10; $i++) {
            Cache::forget("{$this->getCachePrefix()}.all.15.{$i}");
        }
    }

    /**
     * Get cache prefix
     */
    private function getCachePrefix(): string
    {
        return self::CACHE_PREFIX;
    }
}
