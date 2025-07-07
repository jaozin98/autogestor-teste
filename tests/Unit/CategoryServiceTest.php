<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use App\Services\CategoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class CategoryServiceTest extends TestCase
{
    use RefreshDatabase;

    private CategoryService $categoryService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryService = new \App\Services\CategoryService(new \App\Repositories\CategoryRepository());
    }

    /** @test */
    public function it_can_get_all_categories_with_pagination()
    {
        // Arrange
        Category::factory()->count(20)->create();

        // Act
        $result = $this->categoryService->getAllCategories(10);

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(10, $result->perPage());
        $this->assertEquals(20, $result->total());
    }

    /** @test */
    public function it_can_get_active_categories_for_select()
    {
        // Arrange
        \Illuminate\Support\Facades\Cache::flush(); // Limpar cache
        Category::factory()->count(3)->create(['is_active' => true]);
        Category::factory()->count(2)->create(['is_active' => false]);

        // Verificar se as categorias foram criadas corretamente
        $this->assertEquals(3, Category::where('is_active', true)->count());
        $this->assertEquals(2, Category::where('is_active', false)->count());

        // Act
        $result = $this->categoryService->getActiveCategoriesForSelect();

        // Assert
        $this->assertCount(3, $result);
        // O método retorna apenas id e name, não is_active, mas usa o scope active()
        // então sabemos que são apenas categorias ativas
    }

    /** @test */
    public function it_can_find_category_by_id_with_relationships()
    {
        // Arrange
        $category = Category::factory()->create();
        Product::factory()->count(3)->create(['category_id' => $category->id]);

        // Act
        $result = $this->categoryService->findCategory($category->id);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($category->id, $result->id);
        $this->assertTrue($result->relationLoaded('products'));
        $this->assertEquals(3, $result->products_count);
    }

    /** @test */
    public function it_can_create_a_new_category()
    {
        // Arrange
        $data = [
            'name' => 'Test Category',
            'description' => 'Test Description',
            'is_active' => true,
        ];

        // Act
        $result = $this->categoryService->createCategory($data);

        // Assert
        $this->assertInstanceOf(Category::class, $result);
        $this->assertEquals('Test Category', $result->name);
        $this->assertEquals('Test Description', $result->description);
        $this->assertTrue($result->is_active);
        $this->assertDatabaseHas('categories', $data);
    }

    /** @test */
    public function it_can_update_an_existing_category()
    {
        // Arrange
        $category = Category::factory()->create(['name' => 'Old Name']);
        $data = ['name' => 'New Name'];

        // Act
        $result = $this->categoryService->updateCategory($category, $data);

        // Assert
        $this->assertTrue($result);
        $this->assertEquals('New Name', $category->fresh()->name);
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'New Name']);
    }

    /** @test */
    public function it_can_delete_a_category_without_products()
    {
        // Arrange
        $category = Category::factory()->create();

        // Act
        $result = $this->categoryService->deleteCategory($category);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    /** @test */
    public function it_cannot_delete_category_with_products()
    {
        // Arrange
        $category = Category::factory()->create();
        Product::factory()->create(['category_id' => $category->id]);

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Não é possível excluir a categoria '{$category->name}' pois ela possui produtos associados.");

        $this->categoryService->deleteCategory($category);
    }

    /** @test */
    public function it_can_search_categories_by_name()
    {
        // Arrange
        Category::factory()->create(['name' => 'Electronics']);
        Category::factory()->create(['name' => 'Clothing']);
        Category::factory()->create(['name' => 'Electronics Accessories']);

        // Act
        $result = $this->categoryService->searchCategories('Electronics');

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(2, $result->total());
        $this->assertTrue($result->every(fn($category) => str_contains($category->name, 'Electronics')));
    }

    /** @test */
    public function it_can_search_categories_by_description()
    {
        // Arrange
        Category::factory()->create(['description' => 'Electronic devices']);
        Category::factory()->create(['description' => 'Clothing items']);
        Category::factory()->create(['description' => 'Electronic accessories']);

        // Act
        $result = $this->categoryService->searchCategories('Electronic');

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(2, $result->total());
    }

    /** @test */
    public function it_can_get_category_statistics()
    {
        // Arrange
        Category::factory()->count(3)->create(['is_active' => true]);
        Category::factory()->count(2)->create(['is_active' => false]);

        $categoryWithProducts = Category::factory()->create(['is_active' => true]);
        Product::factory()->count(3)->create(['category_id' => $categoryWithProducts->id]);

        // Act
        $result = $this->categoryService->getCategoryStats();

        // Assert
        $this->assertEquals(6, $result['total']);
        $this->assertEquals(4, $result['active']); // 3 + 1 (categoryWithProducts)
        $this->assertEquals(2, $result['inactive']);
        $this->assertEquals(1, $result['with_products']);
        $this->assertEquals(5, $result['without_products']);
    }

    /** @test */
    public function it_can_toggle_category_status()
    {
        // Arrange
        $category = Category::factory()->create(['is_active' => true]);

        // Act
        $result = $this->categoryService->toggleCategoryStatus($category);

        // Assert
        $this->assertTrue($result);
        $this->assertFalse($category->fresh()->is_active);

        // Toggle back
        $result = $this->categoryService->toggleCategoryStatus($category);
        $this->assertTrue($result);
        $this->assertTrue($category->fresh()->is_active);
    }

    /** @test */
    public function it_can_get_top_categories()
    {
        // Arrange
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();
        $category3 = Category::factory()->create();

        Product::factory()->count(5)->create(['category_id' => $category1->id]);
        Product::factory()->count(3)->create(['category_id' => $category2->id]);
        Product::factory()->count(1)->create(['category_id' => $category3->id]);

        // Act
        $result = $this->categoryService->getTopCategories(2);

        // Assert
        $this->assertCount(2, $result);
        $this->assertEquals($category1->id, $result->first()->id);
        $this->assertEquals(5, $result->first()->products_count);
    }
}
