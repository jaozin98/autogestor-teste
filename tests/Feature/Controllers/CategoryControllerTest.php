<?php

namespace Tests\Feature\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Services\CategoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Executar seeders
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);

        // Criar usuário com role 'user' (não admin)
        $this->user = User::factory()->create();
        $this->user->assignRole('user');
    }

    /** @test */
    public function it_shows_category_list()
    {
        $this->actingAs($this->user)
            ->get(route('categories.index'))
            ->assertStatus(200)
            ->assertViewIs('categories.index');
    }

    /** @test */
    public function it_searches_categories()
    {
        Category::factory()->create(['name' => 'Categoria Teste']);
        $this->actingAs($this->user)
            ->get(route('categories.index', ['search' => 'Teste']))
            ->assertStatus(200)
            ->assertViewIs('categories.index');
    }

    /** @test */
    public function it_shows_create_form()
    {
        $this->actingAs($this->user)
            ->get(route('categories.create'))
            ->assertStatus(200)
            ->assertViewIs('categories.create');
    }

    /** @test */
    public function it_stores_category_successfully()
    {
        $data = [
            'name' => 'Categoria Nova',
            'description' => 'Descrição da categoria',
            'is_active' => true
        ];
        $this->actingAs($this->user)
            ->post(route('categories.store'), $data)
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas('success');
        $this->assertDatabaseHas('categories', ['name' => 'Categoria Nova']);
    }

    /** @test */
    public function it_validates_required_fields_on_store()
    {
        $this->actingAs($this->user)
            ->post(route('categories.store'), [])
            ->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function it_validates_unique_name_on_store()
    {
        Category::factory()->create(['name' => 'Categoria Existente']);

        $this->actingAs($this->user)
            ->post(route('categories.store'), ['name' => 'Categoria Existente'])
            ->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function it_handles_category_service_exception_on_store()
    {
        $mock = $this->createMock(CategoryService::class);
        $mock->method('createCategory')
            ->willThrowException(new \Exception('Erro ao criar categoria'));
        $this->app->instance(CategoryService::class, $mock);

        $this->actingAs($this->user)
            ->post(route('categories.store'), ['name' => 'Categoria Teste'])
            ->assertSessionHas('error');
    }

    /** @test */
    public function it_shows_category_details()
    {
        $category = Category::factory()->create();
        $this->actingAs($this->user)
            ->get(route('categories.show', $category))
            ->assertStatus(200)
            ->assertViewIs('categories.show');
    }

    /** @test */
    public function it_handles_category_not_found_on_show()
    {
        $mock = $this->createMock(CategoryService::class);
        $mock->method('findCategory')
            ->willReturn(null);
        $this->app->instance(CategoryService::class, $mock);
        $category = Category::factory()->create();
        $this->actingAs($this->user)
            ->get(route('categories.show', $category))
            ->assertStatus(404);
    }

    /** @test */
    public function it_shows_edit_form()
    {
        $category = Category::factory()->create();
        $this->actingAs($this->user)
            ->get(route('categories.edit', $category))
            ->assertStatus(200)
            ->assertViewIs('categories.edit');
    }

    /** @test */
    public function it_handles_category_not_found_on_edit()
    {
        $mock = $this->createMock(CategoryService::class);
        $mock->method('findCategory')
            ->willReturn(null);
        $this->app->instance(CategoryService::class, $mock);
        $category = Category::factory()->create();
        $this->actingAs($this->user)
            ->get(route('categories.edit', $category))
            ->assertStatus(404);
    }

    /** @test */
    public function it_updates_category_successfully()
    {
        $category = Category::factory()->create();
        $data = [
            'name' => 'Categoria Atualizada',
            'description' => 'Descrição atualizada',
            'is_active' => false
        ];
        $this->actingAs($this->user)
            ->put(route('categories.update', $category), $data)
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas('success');
        $this->assertDatabaseHas('categories', ['name' => 'Categoria Atualizada']);
    }

    /** @test */
    public function it_validates_required_fields_on_update()
    {
        $category = Category::factory()->create();
        $this->actingAs($this->user)
            ->put(route('categories.update', $category), [])
            ->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function it_validates_unique_name_on_update()
    {
        $category1 = Category::factory()->create(['name' => 'Categoria 1']);
        $category2 = Category::factory()->create(['name' => 'Categoria 2']);

        $this->actingAs($this->user)
            ->put(route('categories.update', $category2), ['name' => 'Categoria 1'])
            ->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function it_deletes_category_successfully()
    {
        $category = Category::factory()->create();
        $this->actingAs($this->user)
            ->delete(route('categories.destroy', $category))
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas('success');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    /** @test */
    public function it_toggles_category_status_successfully()
    {
        $category = Category::factory()->create(['is_active' => true]);
        $this->actingAs($this->user)
            ->patch(route('categories.toggle-status', $category))
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas('success');
        $this->assertFalse($category->fresh()->is_active);
    }

    /** @test */
    public function it_handles_error_on_toggle_status()
    {
        $mock = $this->createMock(CategoryService::class);
        $mock->method('toggleCategoryStatus')
            ->willThrowException(new \Exception('Erro ao alterar status'));
        $this->app->instance(CategoryService::class, $mock);

        $category = Category::factory()->create();
        $this->actingAs($this->user)
            ->patch(route('categories.toggle-status', $category))
            ->assertSessionHas('error');
    }
}
