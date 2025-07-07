<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create();
        $category = Category::factory();
        $brand = Brand::factory();

        return [
            'name' => $faker->words(3, true),
            'description' => $faker->optional(0.7)->paragraph(),
            'price' => $faker->randomFloat(2, 10, 1000),
            'cost_price' => $faker->optional(0.6)->randomFloat(2, 5, 800),
            'sale_price' => $faker->optional(0.4)->randomFloat(2, 15, 1200),
            'stock' => $faker->numberBetween(0, 100),
            'min_stock' => $faker->numberBetween(0, 10),
            'max_stock' => $faker->optional(0.5)->numberBetween(50, 200),
            'sku' => 'SKU-' . $faker->unique()->numberBetween(100000, 999999),
            'barcode' => $faker->unique()->numberBetween(1000000000000, 9999999999999),
            'is_active' => $faker->boolean(80), // 80% chance of being active
            'category_id' => $category->id ?? $category,
            'brand_id' => $brand->id ?? $brand,
            'weight' => $faker->optional(0.7)->randomFloat(3, 0.1, 50),
            'height' => $faker->optional(0.6)->randomFloat(2, 1, 100),
            'width' => $faker->optional(0.6)->randomFloat(2, 1, 100),
            'length' => $faker->optional(0.6)->randomFloat(2, 1, 100),
            'specifications' => $faker->optional(0.5)->randomElements([
                'color' => $faker->colorName(),
                'material' => $faker->randomElement(['Plastic', 'Metal', 'Glass', 'Wood', 'Fabric']),
                'warranty' => $faker->randomElement(['1 year', '2 years', '3 years', 'No warranty']),
                'origin' => $faker->country(),
                'model' => $faker->bothify('??-####'),
            ], $faker->numberBetween(1, 4)),
            'images' => $faker->optional(0.4)->randomElements([
                'https://picsum.photos/400/400?random=1',
                'https://picsum.photos/400/400?random=2',
                'https://picsum.photos/400/400?random=3',
            ], $faker->numberBetween(1, 3)),
            'last_purchase_date' => $faker->optional(0.3)->dateTimeBetween('-1 year', 'now'),
            'last_sale_date' => $faker->optional(0.2)->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Indicate that the product is active.
     */
    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the product is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the product is in stock.
     */
    public function inStock(int $quantity = null): static
    {
        return $this->state(function (array $attributes, $factory) {
            return [
                'stock' => $quantity ?? $factory->faker->numberBetween(1, 100),
            ];
        });
    }

    /**
     * Indicate that the product is out of stock.
     */
    public function outOfStock(): static
    {
        return $this->state(fn(array $attributes) => [
            'stock' => 0,
        ]);
    }

    /**
     * Indicate that the product has low stock.
     */
    public function lowStock(): static
    {
        return $this->state(function (array $attributes, $factory) {
            return [
                'stock' => $factory->faker->numberBetween(1, 5),
                'min_stock' => $factory->faker->numberBetween(5, 10),
            ];
        });
    }

    /**
     * Indicate that the product is expensive.
     */
    public function expensive(): static
    {
        return $this->state(function (array $attributes, $factory) {
            return [
                'price' => $factory->faker->randomFloat(2, 500, 5000),
            ];
        });
    }

    /**
     * Indicate that the product is cheap.
     */
    public function cheap(): static
    {
        return $this->state(function (array $attributes, $factory) {
            return [
                'price' => $factory->faker->randomFloat(2, 1, 50),
            ];
        });
    }

    /**
     * Indicate that the product has a specific category.
     */
    public function forCategory(Category $category): static
    {
        return $this->state(fn(array $attributes) => [
            'category_id' => $category->id,
        ]);
    }

    /**
     * Indicate that the product has a specific brand.
     */
    public function forBrand(Brand $brand): static
    {
        return $this->state(fn(array $attributes) => [
            'brand_id' => $brand->id,
        ]);
    }

    /**
     * Indicate that the product has images.
     */
    public function withImages(): static
    {
        return $this->state(function (array $attributes, $factory) {
            return [
                'images' => [
                    'https://picsum.photos/400/400?random=' . $factory->faker->numberBetween(1, 100),
                    'https://picsum.photos/400/400?random=' . $factory->faker->numberBetween(101, 200),
                ],
            ];
        });
    }

    /**
     * Indicate that the product has specifications.
     */
    public function withSpecifications(): static
    {
        return $this->state(function (array $attributes, $factory) {
            return [
                'specifications' => [
                    'color' => $factory->faker->colorName(),
                    'material' => $factory->faker->randomElement(['Plastic', 'Metal', 'Glass', 'Wood', 'Fabric']),
                    'warranty' => $factory->faker->randomElement(['1 year', '2 years', '3 years', 'No warranty']),
                    'origin' => $factory->faker->country(),
                    'model' => $factory->faker->bothify('??-####'),
                    'dimensions' => $factory->faker->randomFloat(2, 1, 100) . ' x ' . $factory->faker->randomFloat(2, 1, 100) . ' x ' . $factory->faker->randomFloat(2, 1, 100) . ' cm',
                ],
            ];
        });
    }
}
