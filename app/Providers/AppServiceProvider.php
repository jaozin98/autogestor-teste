<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\BrandRepositoryInterface;
use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Services\ProductServiceInterface;
use App\Contracts\Services\BrandServiceInterface;
use App\Contracts\Services\CategoryServiceInterface;
use App\Contracts\Services\UserServiceInterface;
use App\Repositories\ProductRepository;
use App\Repositories\BrandRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\UserRepository;
use App\Services\ProductService;
use App\Services\BrandService;
use App\Services\CategoryService;
use App\Services\UserService;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\User;
use App\Observers\ProductObserver;
use App\Observers\BrandObserver;
use App\Observers\CategoryObserver;
use App\Observers\UserObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind Product Repository and Service
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(ProductServiceInterface::class, ProductService::class);

        // Bind Brand Repository and Service
        $this->app->bind(BrandRepositoryInterface::class, BrandRepository::class);
        $this->app->bind(BrandServiceInterface::class, BrandService::class);

        // Bind Category Repository and Service
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);

        // Bind User Repository and Service
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Product Observer
        Product::observe(ProductObserver::class);

        // Register Brand Observer
        Brand::observe(BrandObserver::class);

        // Register Category Observer
        Category::observe(CategoryObserver::class);

        // Register User Observer
        User::observe(UserObserver::class);
    }
}
