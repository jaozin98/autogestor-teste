<?php

namespace App\Services;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Services\ProductServiceInterface;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Exception;

class ProductService implements ProductServiceInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    /**
     * Get all products with pagination and caching
     */
    public function getAllProducts(int $perPage = 15): LengthAwarePaginator
    {
        try {
            return $this->productRepository->getAllProducts($perPage);
        } catch (Exception $e) {
            Log::error('Erro ao buscar produtos', [
                'error' => $e->getMessage(),
                'per_page' => $perPage,
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Get active products for select dropdowns
     */
    public function getActiveProductsForSelect(): Collection
    {
        try {
            return $this->productRepository->getActiveProductsForSelect();
        } catch (Exception $e) {
            Log::error('Erro ao buscar produtos ativos', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Find product by ID with relationships
     */
    public function findProduct(int $id): ?Product
    {
        try {
            return $this->productRepository->findProduct($id);
        } catch (Exception $e) {
            Log::error('Erro ao buscar produto por ID', [
                'product_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Find product by SKU
     */
    public function findProductBySku(string $sku): ?Product
    {
        try {
            return $this->productRepository->findProductBySku($sku);
        } catch (Exception $e) {
            Log::error('Erro ao buscar produto por SKU', [
                'sku' => $sku,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Find product by barcode
     */
    public function findProductByBarcode(string $barcode): ?Product
    {
        try {
            return $this->productRepository->findProductByBarcode($barcode);
        } catch (Exception $e) {
            Log::error('Erro ao buscar produto por código de barras', [
                'barcode' => $barcode,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Create a new product with validation and logging
     */
    public function createProduct(array $data): Product
    {
        try {
            DB::beginTransaction();

            // Validate data
            $validatedData = $this->validateProductData($data);

            // Generate SKU if not provided
            if (empty($validatedData['sku'])) {
                $validatedData['sku'] = $this->generateSku($validatedData['name'], $validatedData['category_id']);
            }

            // Set default values
            $validatedData['is_active'] = $validatedData['is_active'] ?? true;
            $validatedData['stock'] = $validatedData['stock'] ?? 0;
            $validatedData['min_stock'] = $validatedData['min_stock'] ?? 0;

            // Create product
            $product = $this->productRepository->createProduct($validatedData);

            Log::info('Produto criado com sucesso', [
                'product_id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'user_id' => Auth::id() ?? null,
            ]);

            DB::commit();
            return $product;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar produto', [
                'data' => $data,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Update an existing product with validation and logging
     */
    public function updateProduct(Product $product, array $data): bool
    {
        try {
            DB::beginTransaction();

            // Validate data
            $validatedData = $this->validateProductData($data, $product);

            // Store old values for logging
            $oldValues = $product->only(['name', 'price', 'stock', 'is_active']);

            // Update product
            $updated = $this->productRepository->updateProduct($product, $validatedData);

            if ($updated) {
                Log::info('Produto atualizado com sucesso', [
                    'product_id' => $product->id,
                    'old_values' => $oldValues,
                    'new_values' => $validatedData,
                    'user_id' => Auth::id() ?? null,
                ]);
            }

            DB::commit();
            return $updated;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar produto', [
                'product_id' => $product->id,
                'data' => $data,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Delete a product with validation and logging
     */
    public function deleteProduct(Product $product): bool
    {
        try {
            DB::beginTransaction();

            // Check if product can be deleted (business rules)
            if ($product->stock > 0) {
                throw new Exception("Não é possível excluir o produto '{$product->name}' pois ainda possui estoque.");
            }

            $productName = $product->name;
            $productId = $product->id;

            $deleted = $this->productRepository->deleteProduct($product);

            if ($deleted) {
                Log::info('Produto excluído com sucesso', [
                    'product_id' => $productId,
                    'name' => $productName,
                    'user_id' => Auth::id() ?? null,
                ]);
            }

            DB::commit();
            return $deleted;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao excluir produto', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Search products by name, description, SKU or barcode
     */
    public function searchProducts(string $search, int $perPage = 15): LengthAwarePaginator
    {
        try {
            return $this->productRepository->searchProducts($search, $perPage);
        } catch (Exception $e) {
            Log::error('Erro ao pesquisar produtos', [
                'search' => $search,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Get products by category
     */
    public function getProductsByCategory(int $categoryId, int $perPage = 15): LengthAwarePaginator
    {
        try {
            return $this->productRepository->getProductsByCategory($categoryId, $perPage);
        } catch (Exception $e) {
            Log::error('Erro ao buscar produtos por categoria', [
                'category_id' => $categoryId,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Get products by brand
     */
    public function getProductsByBrand(int $brandId, int $perPage = 15): LengthAwarePaginator
    {
        try {
            return $this->productRepository->getProductsByBrand($brandId, $perPage);
        } catch (Exception $e) {
            Log::error('Erro ao buscar produtos por marca', [
                'brand_id' => $brandId,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Get low stock products
     */
    public function getLowStockProducts(int $perPage = 15): LengthAwarePaginator
    {
        try {
            return $this->productRepository->getLowStockProducts($perPage);
        } catch (Exception $e) {
            Log::error('Erro ao buscar produtos com estoque baixo', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Get out of stock products
     */
    public function getOutOfStockProducts(int $perPage = 15): LengthAwarePaginator
    {
        try {
            return $this->productRepository->getOutOfStockProducts($perPage);
        } catch (Exception $e) {
            Log::error('Erro ao buscar produtos sem estoque', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Get products statistics
     */
    public function getProductStats(): array
    {
        try {
            return $this->productRepository->getProductStats();
        } catch (Exception $e) {
            Log::error('Erro ao buscar estatísticas dos produtos', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Update product stock with validation
     */
    public function updateStock(Product $product, int $quantity, string $operation = 'add'): bool
    {
        try {
            DB::beginTransaction();

            // Validate operation
            if (!in_array($operation, ['add', 'subtract', 'set'])) {
                throw new Exception("Operação inválida: {$operation}");
            }

            // Validate quantity
            if ($quantity < 0) {
                throw new Exception("Quantidade não pode ser negativa");
            }

            $oldStock = $product->stock;
            $updated = $this->productRepository->updateStock($product, $quantity, $operation);

            if ($updated) {
                Log::info('Estoque do produto atualizado', [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'old_stock' => $oldStock,
                    'new_stock' => $product->fresh()->stock,
                    'operation' => $operation,
                    'quantity' => $quantity,
                    'user_id' => Auth::id() ?? null,
                ]);
            }

            DB::commit();
            return $updated;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar estoque do produto', [
                'product_id' => $product->id,
                'operation' => $operation,
                'quantity' => $quantity,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Get top selling products
     */
    public function getTopSellingProducts(int $limit = 10): Collection
    {
        try {
            return $this->productRepository->getTopSellingProducts($limit);
        } catch (Exception $e) {
            Log::error('Erro ao buscar produtos mais vendidos', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Get recently added products
     */
    public function getRecentlyAddedProducts(int $limit = 10): Collection
    {
        try {
            return $this->productRepository->getRecentlyAddedProducts($limit);
        } catch (Exception $e) {
            Log::error('Erro ao buscar produtos recentes', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Toggle product active status
     */
    public function toggleProductStatus(Product $product): bool
    {
        try {
            DB::beginTransaction();

            $oldStatus = $product->is_active;
            $product->is_active = !$oldStatus;
            $updated = $product->save();

            if ($updated) {
                Log::info('Status do produto alterado', [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'old_status' => $oldStatus ? 'ativo' : 'inativo',
                    'new_status' => $product->is_active ? 'ativo' : 'inativo',
                    'user_id' => Auth::id() ?? null,
                ]);
            }

            DB::commit();
            return $updated;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao alterar status do produto', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Bulk update products
     */
    public function bulkUpdateProducts(array $productIds, array $data): int
    {
        try {
            DB::beginTransaction();

            $updatedCount = Product::whereIn('id', $productIds)->update($data);

            Log::info('Produtos atualizados em lote', [
                'product_ids' => $productIds,
                'updated_count' => $updatedCount,
                'data' => $data,
                'user_id' => Auth::id() ?? null,
            ]);

            DB::commit();
            return $updatedCount;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar produtos em lote', [
                'product_ids' => $productIds,
                'data' => $data,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Bulk delete products
     */
    public function bulkDeleteProducts(array $productIds): int
    {
        try {
            DB::beginTransaction();

            // Check if any product has stock
            $productsWithStock = Product::whereIn('id', $productIds)
                ->where('stock', '>', 0)
                ->pluck('name')
                ->toArray();

            if (!empty($productsWithStock)) {
                throw new Exception("Não é possível excluir produtos com estoque: " . implode(', ', $productsWithStock));
            }

            $deletedCount = Product::whereIn('id', $productIds)->delete();

            Log::info('Produtos excluídos em lote', [
                'product_ids' => $productIds,
                'deleted_count' => $deletedCount,
                'user_id' => Auth::id() ?? null,
            ]);

            DB::commit();
            return $deletedCount;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erro ao excluir produtos em lote', [
                'product_ids' => $productIds,
                'error' => $e->getMessage(),
                'user_id' => Auth::id() ?? null,
            ]);
            throw $e;
        }
    }

    /**
     * Import products from CSV
     */
    public function importProductsFromCsv(string $filePath): array
    {
        // Implementation for CSV import
        // This would read CSV file and create products
        return ['success' => 0, 'errors' => []];
    }

    /**
     * Export products to CSV
     */
    public function exportProductsToCsv(array $filters = []): string
    {
        // Implementation for CSV export
        return '';
    }

    /**
     * Generate product SKU
     */
    public function generateSku(string $name, int $categoryId): string
    {
        $category = Category::find($categoryId);
        $categoryPrefix = $category ? strtoupper(substr($category->name, 0, 3)) : 'PRD';

        $nameSlug = Str::slug($name);
        $namePrefix = strtoupper(substr($nameSlug, 0, 3));

        $timestamp = now()->format('ymd');
        $random = strtoupper(Str::random(3));

        return "{$categoryPrefix}-{$namePrefix}-{$timestamp}-{$random}";
    }

    /**
     * Validate product data
     */
    public function validateProductData(array $data, ?Product $product = null): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'nullable|integer|min:0',
            'max_stock' => 'nullable|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . ($product?->id ?? ''),
            'barcode' => 'nullable|string|max:100|unique:products,barcode,' . ($product?->id ?? ''),
            'is_active' => 'boolean',
            'weight' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'specifications' => 'nullable|string',
            'images' => 'nullable|array',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new Exception('Dados do produto inválidos: ' . $validator->errors()->first());
        }

        return $validator->validated();
    }
}
