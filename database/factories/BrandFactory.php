<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $countries = ['Brasil', 'Estados Unidos', 'Alemanha', 'Japão', 'Coreia do Sul', 'China', 'França', 'Itália', 'Reino Unido', 'Canadá'];
        $brands = [
            'Apple',
            'Samsung',
            'Microsoft',
            'Google',
            'Amazon',
            'Nike',
            'Adidas',
            'Coca-Cola',
            'Pepsi',
            'McDonald\'s',
            'Toyota',
            'Honda',
            'BMW',
            'Mercedes-Benz',
            'Volkswagen',
            'Ford',
            'General Motors',
            'Tesla',
            'SpaceX',
            'Netflix'
        ];

        return [
            'name' => fake()->unique()->company(),
            'country_of_origin' => fake()->optional(0.8)->randomElement($countries),
            'founded_year' => fake()->optional(0.7)->numberBetween(1800, date('Y')),
            'website' => fake()->optional(0.6)->url(),
            'description' => fake()->optional(0.5)->paragraph(2),
        ];
    }

    /**
     * Indicate that the brand is from Brazil.
     */
    public function brazilian(): static
    {
        return $this->state(fn(array $attributes) => [
            'country_of_origin' => 'Brasil',
        ]);
    }

    /**
     * Indicate that the brand is from USA.
     */
    public function american(): static
    {
        return $this->state(fn(array $attributes) => [
            'country_of_origin' => 'Estados Unidos',
        ]);
    }

    /**
     * Indicate that the brand is from Germany.
     */
    public function german(): static
    {
        return $this->state(fn(array $attributes) => [
            'country_of_origin' => 'Alemanha',
        ]);
    }

    /**
     * Indicate that the brand is from Japan.
     */
    public function japanese(): static
    {
        return $this->state(fn(array $attributes) => [
            'country_of_origin' => 'Japão',
        ]);
    }

    /**
     * Indicate that the brand was founded in a specific year range.
     */
    public function foundedBetween(int $startYear, int $endYear): static
    {
        return $this->state(fn(array $attributes) => [
            'founded_year' => fake()->numberBetween($startYear, $endYear),
        ]);
    }

    /**
     * Indicate that the brand has a website.
     */
    public function withWebsite(): static
    {
        return $this->state(fn(array $attributes) => [
            'website' => fake()->url(),
        ]);
    }

    /**
     * Indicate that the brand has a description.
     */
    public function withDescription(): static
    {
        return $this->state(fn(array $attributes) => [
            'description' => fake()->paragraph(2),
        ]);
    }

    /**
     * Indicate that the brand is a tech company.
     */
    public function tech(): static
    {
        $techBrands = ['Apple', 'Microsoft', 'Google', 'Amazon', 'Samsung', 'Sony', 'Intel', 'AMD', 'NVIDIA', 'Oracle'];

        return $this->state(fn(array $attributes) => [
            'name' => fake()->unique()->randomElement($techBrands),
            'website' => fake()->url(),
            'description' => fake()->sentence(10),
        ]);
    }

    /**
     * Indicate that the brand is a car manufacturer.
     */
    public function automotive(): static
    {
        $carBrands = ['Toyota', 'Honda', 'BMW', 'Mercedes-Benz', 'Volkswagen', 'Ford', 'General Motors', 'Tesla', 'Ferrari', 'Porsche'];

        return $this->state(fn(array $attributes) => [
            'name' => fake()->unique()->randomElement($carBrands),
            'founded_year' => fake()->numberBetween(1900, 2000),
        ]);
    }
}
