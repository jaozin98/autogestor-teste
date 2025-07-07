<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;

class SampleDataSeeder extends Seeder
{
    /**
     * Executar os seeds do banco de dados.
     */
    public function run(): void
    {
        // Criar categorias
        $eletronicos = Category::firstOrCreate(
            ['name' => 'Eletrônicos'],
            ['description' => 'Produtos eletrônicos e tecnológicos']
        );

        $roupas = Category::firstOrCreate(
            ['name' => 'Roupas'],
            ['description' => 'Vestuário e acessórios']
        );

        $livros = Category::firstOrCreate(
            ['name' => 'Livros'],
            ['description' => 'Livros e publicações']
        );

        // Criar marcas
        $apple = Brand::firstOrCreate(
            ['name' => 'Apple'],
            [
                'country_of_origin' => 'Estados Unidos',
                'founded_year' => 1976,
                'website' => 'https://apple.com'
            ]
        );

        $samsung = Brand::firstOrCreate(
            ['name' => 'Samsung'],
            [
                'country_of_origin' => 'Coreia do Sul',
                'founded_year' => 1938,
                'website' => 'https://samsung.com'
            ]
        );

        $nike = Brand::firstOrCreate(
            ['name' => 'Nike'],
            [
                'country_of_origin' => 'Estados Unidos',
                'founded_year' => 1964,
                'website' => 'https://nike.com'
            ]
        );

        // Criar produtos
        Product::firstOrCreate(
            ['name' => 'iPhone 15'],
            [
                'price' => 5999.99,
                'category_id' => $eletronicos->id,
                'brand_id' => $apple->id,
                'description' => 'Smartphone da Apple com tecnologia avançada'
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Galaxy S24'],
            [
                'price' => 4999.99,
                'category_id' => $eletronicos->id,
                'brand_id' => $samsung->id,
                'description' => 'Smartphone Samsung com câmera profissional'
            ]
        );

        Product::firstOrCreate(
            ['name' => 'Tênis Air Max'],
            [
                'price' => 899.99,
                'category_id' => $roupas->id,
                'brand_id' => $nike->id,
                'description' => 'Tênis esportivo com tecnologia Air Max'
            ]
        );
    }
}
