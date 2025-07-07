<?php

namespace Tests\Feature\Controllers;

use App\Models\Brand;
use App\Models\User;
use App\Services\BrandService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class BrandControllerTest extends TestCase
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
    public function it_shows_brand_list()
    {
        $this->actingAs($this->user)
            ->get(route('brands.index'))
            ->assertStatus(200)
            ->assertViewIs('brands.index');
    }

    /** @test */
    public function it_searches_brands()
    {
        Brand::factory()->create(['name' => 'Marca Teste']);
        $this->actingAs($this->user)
            ->get(route('brands.index', ['search' => 'Teste']))
            ->assertStatus(200)
            ->assertViewIs('brands.index');
    }

    /** @test */
    public function it_filters_brands_by_status()
    {
        Brand::factory()->create(['is_active' => true]);
        Brand::factory()->create(['is_active' => false]);

        $this->actingAs($this->user)
            ->get(route('brands.index', ['status' => 'active']))
            ->assertStatus(200)
            ->assertViewIs('brands.index');

        $this->actingAs($this->user)
            ->get(route('brands.index', ['status' => 'inactive']))
            ->assertStatus(200)
            ->assertViewIs('brands.index');
    }

    /** @test */
    public function it_shows_create_form()
    {
        $this->actingAs($this->user)
            ->get(route('brands.create'))
            ->assertStatus(200)
            ->assertViewIs('brands.create');
    }

    /** @test */
    public function it_stores_brand_successfully()
    {
        $data = [
            'name' => 'Marca Nova',
            'description' => 'Descrição da marca',
            'is_active' => true
        ];
        $this->actingAs($this->user)
            ->post(route('brands.store'), $data)
            ->assertRedirect(route('brands.index'))
            ->assertSessionHas('success');
        $this->assertDatabaseHas('brands', ['name' => 'Marca Nova']);
    }

    /** @test */
    public function it_validates_required_fields_on_store()
    {
        $this->actingAs($this->user)
            ->post(route('brands.store'), [])
            ->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function it_validates_unique_name_on_store()
    {
        Brand::factory()->create(['name' => 'Marca Existente']);

        $this->actingAs($this->user)
            ->post(route('brands.store'), ['name' => 'Marca Existente'])
            ->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function it_handles_brand_service_exception_on_store()
    {
        $mock = $this->createMock(BrandService::class);
        $mock->method('createBrand')
            ->willThrowException(new \Exception('Erro ao criar marca'));
        $this->app->instance(BrandService::class, $mock);

        $this->actingAs($this->user)
            ->post(route('brands.store'), ['name' => 'Marca Teste'])
            ->assertSessionHas('error');
    }

    /** @test */
    public function it_shows_brand_details()
    {
        $brand = Brand::factory()->create();
        $this->actingAs($this->user)
            ->get(route('brands.show', $brand))
            ->assertStatus(200)
            ->assertViewIs('brands.show');
    }

    /** @test */
    public function it_handles_brand_not_found_on_show()
    {
        $mock = $this->createMock(BrandService::class);
        $mock->method('findBrand')
            ->willReturn(null);
        $this->app->instance(BrandService::class, $mock);
        $brand = Brand::factory()->create();
        $this->actingAs($this->user)
            ->get(route('brands.show', $brand))
            ->assertRedirect()
            ->assertSessionHas('error', 'Marca não encontrada.');
    }

    /** @test */
    public function it_shows_edit_form()
    {
        $brand = Brand::factory()->create();
        $this->actingAs($this->user)
            ->get(route('brands.edit', $brand))
            ->assertStatus(200)
            ->assertViewIs('brands.edit');
    }

    /** @test */
    public function it_handles_brand_not_found_on_edit()
    {
        $mock = $this->createMock(BrandService::class);
        $mock->method('findBrand')
            ->willReturn(null);
        $this->app->instance(BrandService::class, $mock);
        $brand = Brand::factory()->create();
        $this->actingAs($this->user)
            ->get(route('brands.edit', $brand))
            ->assertRedirect()
            ->assertSessionHas('error', 'Marca não encontrada.');
    }

    /** @test */
    public function it_updates_brand_successfully()
    {
        $brand = Brand::factory()->create();
        $data = [
            'name' => 'Marca Atualizada',
            'country_of_origin' => 'Brasil',
            'description' => 'Descrição atualizada',
            'is_active' => false
        ];
        $this->actingAs($this->user)
            ->put(route('brands.update', $brand), $data)
            ->assertRedirect(route('brands.index'))
            ->assertSessionHas('success');
        $this->assertDatabaseHas('brands', ['name' => 'Marca Atualizada']);
    }

    /** @test */
    public function it_validates_required_fields_on_update()
    {
        $brand = Brand::factory()->create();
        $this->actingAs($this->user)
            ->put(route('brands.update', $brand), [])
            ->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function it_validates_unique_name_on_update()
    {
        $brand1 = Brand::factory()->create(['name' => 'Marca 1']);
        $brand2 = Brand::factory()->create(['name' => 'Marca 2']);

        $this->actingAs($this->user)
            ->put(route('brands.update', $brand2), ['name' => 'Marca 1'])
            ->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function it_deletes_brand_successfully()
    {
        $brand = Brand::factory()->create();
        $this->actingAs($this->user)
            ->delete(route('brands.destroy', $brand))
            ->assertRedirect(route('brands.index'))
            ->assertSessionHas('success');
        $this->assertDatabaseMissing('brands', ['id' => $brand->id]);
    }

    /** @test */
    public function it_toggles_brand_status_successfully()
    {
        $brand = Brand::factory()->create(['is_active' => true]);
        $this->actingAs($this->user)
            ->patch(route('brands.toggle-status', $brand))
            ->assertRedirect(route('brands.index'))
            ->assertSessionHas('success');
        $this->assertFalse($brand->fresh()->is_active);
    }

    /** @test */
    public function it_handles_error_on_toggle_status()
    {
        $mock = $this->createMock(BrandService::class);
        $mock->method('toggleBrandStatus')
            ->willThrowException(new \Exception('Erro ao alterar status'));
        $this->app->instance(BrandService::class, $mock);

        $brand = Brand::factory()->create();
        $this->actingAs($this->user)
            ->patch(route('brands.toggle-status', $brand))
            ->assertSessionHas('error');
    }
}
