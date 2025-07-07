<?php

namespace Tests\Feature\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Category $category;
    protected Brand $brand;

    protected function setUp(): void
    {
        parent::setUp();

        // Executar seeders
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);

        // Criar usuário com role 'user' (não admin) e todas as permissões necessárias
        $this->user = User::factory()->create();
        $this->user->assignRole('user');

        // Garantir que o usuário tem as permissões necessárias
        $this->user->givePermissionTo([
            'products.view',
            'products.create',
            'products.edit',
            'products.delete'
        ]);

        // Criar categoria e marca para os testes
        $this->category = Category::factory()->create();
        $this->brand = Brand::factory()->create();
    }

    /** @test */
    public function it_shows_product_list()
    {
        $this->actingAs($this->user)
            ->get(route('products.index'))
            ->assertStatus(200)
            ->assertViewIs('products.index');
    }

    /** @test */
    public function it_searches_products()
    {
        Product::factory()->create(['name' => 'Produto Teste']);
        $this->actingAs($this->user)
            ->get(route('products.index', ['search' => 'Teste']))
            ->assertStatus(200)
            ->assertViewIs('products.index');
    }

    /** @test */
    public function it_filters_products_by_category()
    {
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();

        Product::factory()->create(['category_id' => $category1->id]);
        Product::factory()->create(['category_id' => $category2->id]);

        $this->actingAs($this->user)
            ->get(route('products.index', ['category' => $category1->id]))
            ->assertStatus(200)
            ->assertViewIs('products.index');
    }

    /** @test */
    public function it_filters_products_by_brand()
    {
        $brand1 = Brand::factory()->create();
        $brand2 = Brand::factory()->create();

        Product::factory()->create(['brand_id' => $brand1->id]);
        Product::factory()->create(['brand_id' => $brand2->id]);

        $this->actingAs($this->user)
            ->get(route('products.index', ['brand' => $brand1->id]))
            ->assertStatus(200)
            ->assertViewIs('products.index');
    }

    /** @test */
    public function it_shows_create_form()
    {
        $this->actingAs($this->user)
            ->get(route('products.create'))
            ->assertStatus(200)
            ->assertViewIs('products.create');
    }

    /** @test */
    public function it_stores_product_successfully()
    {
        $data = [
            'name' => 'Produto Novo',
            'description' => 'Descrição do produto',
            'price' => '99.99',
            'stock' => '10',
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'is_active' => true
        ];
        $this->actingAs($this->user)
            ->post(route('products.store'), $data)
            ->assertRedirect(route('products.index'))
            ->assertSessionHas('success');
        $this->assertDatabaseHas('products', ['name' => 'Produto Novo']);
    }

    /** @test */
    public function it_validates_required_fields_on_store()
    {
        $this->actingAs($this->user)
            ->post(route('products.store'), [])
            ->assertSessionHasErrors(['name', 'price', 'category_id']);
    }

    /** @test */
    public function it_validates_price_is_numeric_on_store()
    {
        $this->actingAs($this->user)
            ->post(route('products.store'), [
                'name' => 'Produto Teste',
                'price' => 'invalid',
                'stock' => '10',
                'category_id' => $this->category->id
            ])
            ->assertSessionHasErrors(['price']);
    }

    /** @test */
    public function it_validates_stock_is_positive_on_store()
    {
        $this->actingAs($this->user)
            ->post(route('products.store'), [
                'name' => 'Produto Teste',
                'price' => '99.99',
                'stock' => '-1',
                'category_id' => $this->category->id
            ])
            ->assertSessionHasErrors(['stock']);
    }

    /** @test */
    public function it_handles_product_service_exception_on_store()
    {
        $mock = $this->createMock(ProductService::class);
        $mock->method('createProduct')
            ->willThrowException(new \Exception('Erro ao criar produto'));
        $this->app->instance(ProductService::class, $mock);

        $this->actingAs($this->user)
            ->post(route('products.store'), [
                'name' => 'Produto Teste',
                'price' => '99.99',
                'stock' => '10',
                'category_id' => $this->category->id
            ])
            ->assertSessionHas('error');
    }

    /** @test */
    public function it_shows_product_details()
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id
        ]);
        $this->actingAs($this->user)
            ->get(route('products.show', $product))
            ->assertStatus(200)
            ->assertViewIs('products.show');
    }

    /** @test */
    public function it_handles_product_not_found_on_show()
    {
        $mock = $this->createMock(ProductService::class);
        $mock->method('findProduct')
            ->willReturn(null);
        $this->app->instance(ProductService::class, $mock);
        $product = Product::factory()->create();
        $this->actingAs($this->user)
            ->get(route('products.show', $product))
            ->assertRedirect()
            ->assertSessionHas('error', 'Produto não encontrado.');
    }

    /** @test */
    public function it_shows_edit_form()
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id
        ]);
        $this->actingAs($this->user)
            ->get(route('products.edit', $product))
            ->assertStatus(200)
            ->assertViewIs('products.edit');
    }

    /** @test */
    public function it_handles_product_not_found_on_edit()
    {
        $mock = $this->createMock(ProductService::class);
        $mock->method('findProduct')
            ->willReturn(null);
        $this->app->instance(ProductService::class, $mock);
        $product = Product::factory()->create();
        $this->actingAs($this->user)
            ->get(route('products.edit', $product))
            ->assertRedirect()
            ->assertSessionHas('error', 'Produto não encontrado.');
    }

    /** @test */
    public function it_updates_product_successfully()
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id
        ]);
        $data = [
            'name' => 'Produto Atualizado',
            'price' => '199.99',
            'stock' => '20',
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'is_active' => false
        ];
        $this->actingAs($this->user)
            ->put(route('products.update', $product), $data)
            ->assertRedirect(route('products.index'))
            ->assertSessionHas('success');
        $this->assertDatabaseHas('products', ['name' => 'Produto Atualizado']);
    }

    /** @test */
    public function it_validates_required_fields_on_update()
    {
        $product = Product::factory()->create();
        $this->actingAs($this->user)
            ->put(route('products.update', $product), [])
            ->assertSessionHasErrors(['name', 'price', 'category_id']);
    }

    /** @test */
    public function it_deletes_product_successfully()
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'stock' => 0 // Garantir que o produto não tem estoque
        ]);

        // Verificar se o produto foi criado
        $this->assertDatabaseHas('products', ['id' => $product->id]);

        $response = $this->actingAs($this->user)
            ->delete(route('products.destroy', $product));

        // Verificar se a resposta foi bem-sucedida
        $response->assertRedirect();
        $response->assertSessionMissing('error');
        $response->assertSessionHas('success');

        // Verificar se o produto foi soft deleted
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    /** @test */
    public function it_toggles_product_status_successfully()
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'is_active' => true
        ]);
        $this->actingAs($this->user)
            ->patch(route('products.toggle-status', $product))
            ->assertSessionHas('success');
        $this->assertFalse($product->fresh()->is_active);
    }

    /** @test */
    public function it_handles_error_on_toggle_status()
    {
        $mock = $this->createMock(ProductService::class);
        $mock->method('toggleProductStatus')
            ->willThrowException(new \Exception('Erro ao alterar status'));
        $this->app->instance(ProductService::class, $mock);

        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id
        ]);
        $this->actingAs($this->user)
            ->patch(route('products.toggle-status', $product))
            ->assertSessionHas('error');
    }
}
