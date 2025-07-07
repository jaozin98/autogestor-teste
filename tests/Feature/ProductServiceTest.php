<?php

namespace Tests\Feature;

use App\Contracts\Services\ProductServiceInterface;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProductServiceInterface $productService;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productService = app(ProductServiceInterface::class);
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_can_get_all_products_with_pagination()
    {
        // Arrange
        Product::factory()->count(25)->create();

        // Act
        $result = $this->productService->getAllProducts(15);

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(15, $result->perPage());
        $this->assertEquals(25, $result->total());
        $this->assertEquals(2, $result->lastPage());
    }

    /** @test */
    public function it_can_get_active_products_for_select()
    {
        // Arrange
        \Illuminate\Support\Facades\Cache::flush(); // Limpar cache
        Product::factory()->count(3)->create(['is_active' => true]);
        Product::factory()->count(2)->create(['is_active' => false]);

        // Verificar se os produtos foram criados corretamente
        $this->assertEquals(3, Product::where('is_active', true)->count());
        $this->assertEquals(2, Product::where('is_active', false)->count());

        // Act
        $result = $this->productService->getActiveProductsForSelect();

        // Assert
        $this->assertCount(3, $result);
        // O método retorna apenas id, name e sku, não is_active, mas usa where('is_active', true)
        // então sabemos que são apenas produtos ativos
    }

    /** @test */
    public function it_can_find_product_by_id_with_relationships()
    {
        // Arrange
        $product = Product::factory()->create();

        // Act
        $result = $this->productService->findProduct($product->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($product->id, $result->id);
        $this->assertTrue($result->relationLoaded('category'));
        $this->assertTrue($result->relationLoaded('brand'));
    }

    /** @test */
    public function it_can_find_product_by_sku()
    {
        // Arrange
        $product = Product::factory()->create(['sku' => 'TEST-SKU-123']);

        // Act
        $result = $this->productService->findProductBySku('TEST-SKU-123');

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($product->id, $result->id);
        $this->assertEquals('TEST-SKU-123', $result->sku);
    }

    /** @test */
    public function it_can_find_product_by_barcode()
    {
        // Arrange
        $product = Product::factory()->create(['barcode' => '1234567890123']);

        // Act
        $result = $this->productService->findProductByBarcode('1234567890123');

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($product->id, $result->id);
        $this->assertEquals('1234567890123', $result->barcode);
    }

    /** @test */
    public function it_can_create_a_new_product()
    {
        // Arrange
        $category = Category::factory()->create();
        $brand = Brand::factory()->create();

        $data = [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 99.99,
            'stock' => 10,
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'is_active' => true,
        ];

        // Act
        $result = $this->productService->createProduct($data);

        // Assert
        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals('Test Product', $result->name);
        $this->assertEquals('Test Description', $result->description);
        $this->assertEquals(99.99, $result->price);
        $this->assertEquals(10, $result->stock);
        $this->assertTrue($result->is_active);
        $this->assertNotNull($result->sku);
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'price' => 99.99,
            'category_id' => $category->id,
            'brand_id' => $brand->id,
        ]);
    }

    /** @test */
    public function it_can_update_an_existing_product()
    {
        // Arrange
        $category = Category::factory()->create();
        $brand = Brand::factory()->create();
        $product = Product::factory()->create(['name' => 'Old Name']);
        $data = [
            'name' => 'New Name',
            'price' => 149.99,
            'stock' => 10,
            'category_id' => $category->id,
            'brand_id' => $brand->id,
        ];

        // Act
        $result = $this->productService->updateProduct($product, $data);

        // Assert
        $this->assertTrue($result);
        $this->assertEquals('New Name', $product->fresh()->name);
        $this->assertEquals(149.99, $product->fresh()->price);
        $this->assertEquals(10, $product->fresh()->stock);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'New Name',
            'price' => 149.99,
            'stock' => 10,
        ]);
    }

    /** @test */
    public function it_can_delete_a_product_without_stock()
    {
        // Arrange
        $product = Product::factory()->create(['stock' => 0]);

        // Act
        $result = $this->productService->deleteProduct($product);

        // Assert
        $this->assertTrue($result);
        $this->assertNotNull($product->fresh()->deleted_at);
    }

    /** @test */
    public function it_cannot_delete_product_with_stock()
    {
        // Arrange
        $product = Product::factory()->create(['stock' => 5]);

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Não é possível excluir o produto '{$product->name}' pois ainda possui estoque.");

        $this->productService->deleteProduct($product);
    }

    /** @test */
    public function it_can_search_products_by_name()
    {
        // Arrange
        \App\Models\Product::query()->forceDelete();
        Product::factory()->create(['name' => 'Iphone 15']);
        Product::factory()->create(['name' => 'Iphone 14']);

        // Act
        $result = $this->productService->searchProducts('iPhone');

        // Debug
        // dump($result->pluck('name'));

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(2, $result->total());
        $this->assertTrue($result->every(fn($product) => stripos($product->name, 'iphone') !== false));
    }

    /** @test */
    public function it_can_search_products_by_sku()
    {
        // Arrange
        Product::factory()->create(['sku' => 'IPHONE-15-001']);
        Product::factory()->create(['sku' => 'SAMSUNG-001']);
        Product::factory()->create(['sku' => 'IPHONE-14-001']);

        // Act
        $result = $this->productService->searchProducts('IPHONE');

        // Assert
        $this->assertEquals(2, $result->total());
        $this->assertTrue($result->every(fn($product) => str_contains($product->sku, 'IPHONE')));
    }

    /** @test */
    public function it_can_get_products_by_category()
    {
        // Arrange
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();

        Product::factory()->count(3)->create(['category_id' => $category1->id]);
        Product::factory()->count(2)->create(['category_id' => $category2->id]);

        // Act
        $result = $this->productService->getProductsByCategory($category1->id);

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(3, $result->total());
        $this->assertTrue($result->every(fn($product) => $product->category_id === $category1->id));
    }

    /** @test */
    public function it_can_get_products_by_brand()
    {
        // Arrange
        $brand1 = Brand::factory()->create();
        $brand2 = Brand::factory()->create();

        Product::factory()->count(3)->create(['brand_id' => $brand1->id]);
        Product::factory()->count(2)->create(['brand_id' => $brand2->id]);

        // Act
        $result = $this->productService->getProductsByBrand($brand1->id);

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(3, $result->total());
        $this->assertTrue($result->every(fn($product) => $product->brand_id === $brand1->id));
    }

    /** @test */
    public function it_can_get_low_stock_products()
    {
        // Arrange
        Product::factory()->create(['stock' => 5, 'min_stock' => 10]); // Low stock
        Product::factory()->create(['stock' => 15, 'min_stock' => 10]); // Normal stock
        Product::factory()->create(['stock' => 0, 'min_stock' => 10]); // Out of stock

        // Act
        $result = $this->productService->getLowStockProducts();

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(1, $result->total());
        $this->assertEquals(5, $result->first()->stock);
    }

    /** @test */
    public function it_can_get_out_of_stock_products()
    {
        // Arrange
        Product::factory()->create(['stock' => 0]);
        Product::factory()->create(['stock' => 5]);
        Product::factory()->create(['stock' => 0]);

        // Act
        $result = $this->productService->getOutOfStockProducts();

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(2, $result->total());
        $this->assertTrue($result->every(fn($product) => $product->stock === 0));
    }

    /** @test */
    public function it_can_get_product_statistics()
    {
        // Arrange
        Product::factory()->count(3)->create(['is_active' => true, 'stock' => 10]);
        Product::factory()->count(2)->create(['is_active' => false, 'stock' => 10]);
        Product::factory()->count(2)->create(['is_active' => true, 'stock' => 0]);

        // Act
        $result = $this->productService->getProductStats();

        // Assert
        $this->assertArrayHasKey('total', $result);
        $this->assertArrayHasKey('active', $result);
        $this->assertArrayHasKey('inactive', $result);
        $this->assertArrayHasKey('out_of_stock', $result);

        $this->assertEquals(7, $result['total']);
        $this->assertEquals(5, $result['active']);
        $this->assertEquals(2, $result['inactive']);
        $this->assertEquals(2, $result['out_of_stock']);
    }

    /** @test */
    public function it_can_update_product_stock()
    {
        // Arrange
        $product = Product::factory()->create(['stock' => 10]);

        // Act
        $result = $this->productService->updateStock($product, 5, 'add');

        // Assert
        $this->assertTrue($result);
        $this->assertEquals(15, $product->fresh()->stock);
    }

    /** @test */
    public function it_can_subtract_product_stock()
    {
        // Arrange
        $product = Product::factory()->create(['stock' => 10]);

        // Act
        $result = $this->productService->updateStock($product, 3, 'subtract');

        // Assert
        $this->assertTrue($result);
        $this->assertEquals(7, $product->fresh()->stock);
    }

    /** @test */
    public function it_cannot_subtract_more_stock_than_available()
    {
        // Arrange
        $product = Product::factory()->create(['stock' => 10]);

        // Act
        $result = $this->productService->updateStock($product, 15, 'subtract');

        // Assert
        $this->assertTrue($result);
        $this->assertEquals(0, $product->fresh()->stock); // Should not go below 0
    }

    /** @test */
    public function it_can_set_product_stock()
    {
        // Arrange
        $product = Product::factory()->create(['stock' => 10]);

        // Act
        $result = $this->productService->updateStock($product, 25, 'set');

        // Assert
        $this->assertTrue($result);
        $this->assertEquals(25, $product->fresh()->stock);
    }

    /** @test */
    public function it_can_toggle_product_status()
    {
        // Arrange
        $product = Product::factory()->create(['is_active' => true]);

        // Act
        $result = $this->productService->toggleProductStatus($product);

        // Assert
        $this->assertTrue($result);
        $this->assertFalse($product->fresh()->is_active);

        // Toggle back
        $result = $this->productService->toggleProductStatus($product);
        $this->assertTrue($result);
        $this->assertTrue($product->fresh()->is_active);
    }

    /** @test */
    public function it_can_bulk_update_products()
    {
        // Arrange
        $products = Product::factory()->count(3)->create(['is_active' => true]);
        $productIds = $products->pluck('id')->toArray();
        $data = ['is_active' => false];

        // Act
        $result = $this->productService->bulkUpdateProducts($productIds, $data);

        // Assert
        $this->assertEquals(3, $result);
        $this->assertDatabaseMissing('products', ['is_active' => true]);
    }

    /** @test */
    public function it_can_bulk_delete_products_without_stock()
    {
        // Arrange
        $products = Product::factory()->count(3)->create(['stock' => 0]);
        $productIds = $products->pluck('id')->toArray();

        // Act
        $result = $this->productService->bulkDeleteProducts($productIds);

        // Assert
        $this->assertEquals(3, $result);
        foreach ($productIds as $id) {
            $this->assertNotNull(Product::withTrashed()->find($id)->deleted_at);
        }
    }

    /** @test */
    public function it_cannot_bulk_delete_products_with_stock()
    {
        // Arrange
        $products = Product::factory()->count(2)->create(['stock' => 5]);
        $productIds = $products->pluck('id')->toArray();

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Não é possível excluir produtos com estoque:");

        $this->productService->bulkDeleteProducts($productIds);
    }

    /** @test */
    public function it_can_generate_sku_for_product()
    {
        // Arrange
        $category = Category::factory()->create(['name' => 'Electronics']);

        // Act
        $sku = $this->productService->generateSku('iPhone 15 Pro', $category->id);

        // Assert
        $this->assertStringStartsWith('ELE-', $sku);
        $this->assertStringContainsString('IPH', $sku);
        $this->assertStringContainsString(date('ymd'), $sku);
    }

    /** @test */
    public function it_can_validate_product_data()
    {
        // Arrange
        $category = Category::factory()->create();
        $data = [
            'name' => 'Test Product',
            'price' => 99.99,
            'stock' => 10,
            'category_id' => $category->id,
        ];

        // Act
        $result = $this->productService->validateProductData($data);

        // Assert
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('price', $result);
        $this->assertArrayHasKey('stock', $result);
        $this->assertArrayHasKey('category_id', $result);
    }

    /** @test */
    public function it_throws_exception_for_invalid_product_data()
    {
        // Arrange
        $data = [
            'name' => '', // Invalid: empty name
            'price' => -10, // Invalid: negative price
            'category_id' => 999, // Invalid: non-existent category
        ];

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Dados do produto inválidos:');

        $this->productService->validateProductData($data);
    }
}
