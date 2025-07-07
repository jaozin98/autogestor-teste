<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Product;
use App\Services\BrandService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class BrandServiceTest extends TestCase
{
    use RefreshDatabase;

    private BrandService $brandService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->brandService = new \App\Services\BrandService(new \App\Repositories\BrandRepository());
    }

    /** @test */
    public function it_can_get_all_brands_with_pagination()
    {
        // Arrange
        Brand::factory()->count(25)->create();

        // Act
        $result = $this->brandService->getAllBrands(15);

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(15, $result->perPage());
        $this->assertEquals(25, $result->total());
        $this->assertEquals(2, $result->lastPage());
    }

    /** @test */
    public function it_can_get_brands_for_select()
    {
        // Arrange
        Brand::factory()->count(5)->create();

        // Act
        $result = $this->brandService->getBrandsForSelect();

        // Assert
        $this->assertCount(5, $result);
        $this->assertArrayHasKey('id', $result->first()->toArray());
        $this->assertArrayHasKey('name', $result->first()->toArray());
    }

    /** @test */
    public function it_can_find_brand_by_id()
    {
        // Arrange
        $brand = Brand::factory()->create();

        // Act
        $result = $this->brandService->findBrand($brand->id);

        // Assert
        $this->assertInstanceOf(Brand::class, $result);
        $this->assertEquals($brand->id, $result->id);
    }

    /** @test */
    public function it_returns_null_for_non_existent_brand()
    {
        // Act
        $result = $this->brandService->findBrand(999);

        // Assert
        $this->assertNull($result);
    }

    /** @test */
    public function it_can_create_brand()
    {
        // Arrange
        $brandData = [
            'name' => 'Test Brand',
            'country_of_origin' => 'Brazil',
            'founded_year' => 1990,
            'website' => 'https://test.com',
            'description' => 'Test description'
        ];

        // Act
        $result = $this->brandService->createBrand($brandData);

        // Assert
        $this->assertInstanceOf(Brand::class, $result);
        $this->assertEquals('Test Brand', $result->name);
        $this->assertEquals('Brazil', $result->country_of_origin);
        $this->assertEquals(1990, $result->founded_year);
        $this->assertEquals('https://test.com', $result->website);
        $this->assertEquals('Test description', $result->description);
    }

    /** @test */
    public function it_can_update_brand()
    {
        // Arrange
        $brand = Brand::factory()->create();
        $updateData = [
            'name' => 'Updated Brand',
            'country_of_origin' => 'USA'
        ];

        // Act
        $result = $this->brandService->updateBrand($brand, $updateData);

        // Assert
        $this->assertTrue($result);
        $this->assertEquals('Updated Brand', $brand->fresh()->name);
        $this->assertEquals('USA', $brand->fresh()->country_of_origin);
    }

    /** @test */
    public function it_can_delete_brand()
    {
        // Arrange
        $brand = Brand::factory()->create();

        // Act
        $result = $this->brandService->deleteBrand($brand);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('brands', ['id' => $brand->id]);
    }

    /** @test */
    public function it_can_search_brands()
    {
        // Arrange
        Brand::factory()->create(['name' => 'Apple Inc']);
        Brand::factory()->create(['name' => 'Samsung Electronics']);
        Brand::factory()->create(['name' => 'Microsoft Corp']);

        // Act
        $result = $this->brandService->searchBrands('Apple');

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(1, $result->count());
        $this->assertEquals('Apple Inc', $result->first()->name);
    }

    /** @test */
    public function it_can_search_brands_by_country()
    {
        // Arrange
        Brand::factory()->create(['country_of_origin' => 'Brazil']);
        Brand::factory()->create(['country_of_origin' => 'USA']);
        Brand::factory()->create(['country_of_origin' => 'Germany']);

        // Act
        $result = $this->brandService->searchBrands('Brazil');

        // Assert
        $this->assertEquals(1, $result->count());
        $this->assertEquals('Brazil', $result->first()->country_of_origin);
    }

    /** @test */
    public function it_can_get_brand_stats()
    {
        // Arrange
        $brandWithProducts = Brand::factory()->create();
        $brandWithoutProducts = Brand::factory()->create();

        Product::factory()->count(3)->create(['brand_id' => $brandWithProducts->id]);

        // Act
        $stats = $this->brandService->getBrandStats();

        // Assert
        $this->assertArrayHasKey('total', $stats);
        $this->assertArrayHasKey('with_products', $stats);
        $this->assertArrayHasKey('without_products', $stats);
        $this->assertArrayHasKey('recent', $stats);

        $this->assertEquals(2, $stats['total']);
        $this->assertEquals(1, $stats['with_products']);
        $this->assertEquals(1, $stats['without_products']);
    }

    /** @test */
    public function it_orders_brands_by_name()
    {
        // Arrange
        Brand::factory()->create(['name' => 'Zebra']);
        Brand::factory()->create(['name' => 'Apple']);
        Brand::factory()->create(['name' => 'Microsoft']);

        // Act
        $result = $this->brandService->getAllBrands();

        // Assert
        $this->assertEquals('Apple', $result->first()->name);
        $this->assertEquals('Microsoft', $result->get(1)->name);
        $this->assertEquals('Zebra', $result->last()->name);
    }

    /** @test */
    public function it_includes_products_relationship_when_loading_brands()
    {
        // Arrange
        $brand = Brand::factory()->create();
        Product::factory()->count(3)->create(['brand_id' => $brand->id]);

        // Act
        $result = $this->brandService->findBrand($brand->id);

        // Assert
        $this->assertTrue($result->relationLoaded('products'));
        $this->assertCount(3, $result->products);
    }
}
