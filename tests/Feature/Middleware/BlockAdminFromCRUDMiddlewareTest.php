<?php

namespace Tests\Feature\Middleware;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlockAdminFromCRUDMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Executar seeders
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
    }

    /** @test */
    public function it_blocks_admin_users_from_accessing_products()
    {
        $adminUser = User::factory()->create();
        $adminUser->assignRole('admin');

        // Testar acesso a rotas de produtos
        $response = $this->actingAs($adminUser)
            ->get('/products');

        $response->assertRedirect('/home');
        $response->assertSessionHas('error', 'Usuários administradores não têm acesso a esta funcionalidade.');
    }

    /** @test */
    public function it_blocks_admin_users_from_accessing_categories()
    {
        $adminUser = User::factory()->create();
        $adminUser->assignRole('admin');

        // Testar acesso a rotas de categorias
        $response = $this->actingAs($adminUser)
            ->get('/categories');

        $response->assertRedirect('/home');
        $response->assertSessionHas('error', 'Usuários administradores não têm acesso a esta funcionalidade.');
    }

    /** @test */
    public function it_blocks_admin_users_from_accessing_brands()
    {
        $adminUser = User::factory()->create();
        $adminUser->assignRole('admin');

        // Testar acesso a rotas de marcas
        $response = $this->actingAs($adminUser)
            ->get('/brands');

        $response->assertRedirect('/home');
        $response->assertSessionHas('error', 'Usuários administradores não têm acesso a esta funcionalidade.');
    }

    /** @test */
    public function it_allows_non_admin_users_to_access_crud_routes()
    {
        $regularUser = User::factory()->create();
        $regularUser->assignRole('user');

        // Testar acesso a rotas de produtos
        $response = $this->actingAs($regularUser)
            ->get('/products');

        $response->assertStatus(200);

        // Testar acesso a rotas de categorias
        $response = $this->actingAs($regularUser)
            ->get('/categories');

        $response->assertStatus(200);

        // Testar acesso a rotas de marcas
        $response = $this->actingAs($regularUser)
            ->get('/brands');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_blocks_admin_users_from_all_crud_operations()
    {
        $adminUser = User::factory()->create();
        $adminUser->assignRole('admin');

        // Criar entidades para teste
        $product = Product::factory()->create();
        $category = Category::factory()->create();
        $brand = Brand::factory()->create();

        // Testar operações de produtos
        $this->actingAs($adminUser)
            ->get('/products/create')
            ->assertRedirect('/home');

        $this->actingAs($adminUser)
            ->post('/products')
            ->assertRedirect('/home');

        $this->actingAs($adminUser)
            ->get("/products/{$product->id}")
            ->assertRedirect('/home');

        $this->actingAs($adminUser)
            ->get("/products/{$product->id}/edit")
            ->assertRedirect('/home');

        $this->actingAs($adminUser)
            ->put("/products/{$product->id}")
            ->assertRedirect('/home');

        $this->actingAs($adminUser)
            ->delete("/products/{$product->id}")
            ->assertRedirect('/home');

        // Testar operações de categorias
        $this->actingAs($adminUser)
            ->get('/categories/create')
            ->assertRedirect('/home');

        $this->actingAs($adminUser)
            ->post('/categories')
            ->assertRedirect('/home');

        $this->actingAs($adminUser)
            ->get("/categories/{$category->id}")
            ->assertRedirect('/home');

        $this->actingAs($adminUser)
            ->get("/categories/{$category->id}/edit")
            ->assertRedirect('/home');

        $this->actingAs($adminUser)
            ->put("/categories/{$category->id}")
            ->assertRedirect('/home');

        $this->actingAs($adminUser)
            ->delete("/categories/{$category->id}")
            ->assertRedirect('/home');

        // Testar operações de marcas
        $this->actingAs($adminUser)
            ->get('/brands/create')
            ->assertRedirect('/home');

        $this->actingAs($adminUser)
            ->post('/brands')
            ->assertRedirect('/home');

        $this->actingAs($adminUser)
            ->get("/brands/{$brand->id}")
            ->assertRedirect('/home');

        $this->actingAs($adminUser)
            ->get("/brands/{$brand->id}/edit")
            ->assertRedirect('/home');

        $this->actingAs($adminUser)
            ->put("/brands/{$brand->id}")
            ->assertRedirect('/home');

        $this->actingAs($adminUser)
            ->delete("/brands/{$brand->id}")
            ->assertRedirect('/home');
    }

    /** @test */
    public function it_handles_user_without_roles()
    {
        $userWithoutRole = User::factory()->create();

        $response = $this->actingAs($userWithoutRole)
            ->get('/products');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_handles_user_with_multiple_roles_including_admin()
    {
        $user = User::factory()->create();
        $user->assignRole(['admin', 'user']);

        // Mesmo com múltiplas roles, se tiver admin, deve ser bloqueado
        $response = $this->actingAs($user)
            ->get('/products');

        $response->assertRedirect('/home');
    }

    /** @test */
    public function it_works_with_different_http_methods()
    {
        $adminUser = User::factory()->create();
        $adminUser->assignRole('admin');

        $product = Product::factory()->create();

        // Testar diferentes métodos HTTP
        $this->actingAs($adminUser)
            ->post('/products')
            ->assertRedirect('/home');

        $this->actingAs($adminUser)
            ->put("/products/{$product->id}")
            ->assertRedirect('/home');

        $this->actingAs($adminUser)
            ->delete("/products/{$product->id}")
            ->assertRedirect('/home');
    }

    /** @test */
    public function it_allows_admin_users_to_access_non_crud_routes()
    {
        $adminUser = User::factory()->create();
        $adminUser->assignRole('admin');

        // Rotas que não são bloqueadas pelo middleware
        $this->actingAs($adminUser)
            ->get('/home')
            ->assertStatus(200);

        $this->actingAs($adminUser)
            ->get('/users')
            ->assertStatus(200);

        $this->actingAs($adminUser)
            ->get('/roles')
            ->assertStatus(200);
    }
}
