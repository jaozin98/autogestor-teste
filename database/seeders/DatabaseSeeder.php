<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            ['email' => 'admin@autogestor.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );

        // Executar seeders na ordem correta
        $this->call([
            RolePermissionSeeder::class,
            SampleDataSeeder::class,
        ]);
    }
}
