<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserCreationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Criar roles necessárias
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
    }

    public function test_user_is_created_as_active_when_is_active_is_true(): void
    {
        $userService = app(UserService::class);

        $userData = [
            'name' => 'João Silva',
            'email' => 'joao@teste.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'is_active' => true,
            'roles' => ['user']
        ];

        $user = $userService->createUser($userData);

        $this->assertNotNull($user->email_verified_at);
        $this->assertTrue($user->email_verified_at->isToday());
    }

    public function test_user_is_created_as_inactive_when_is_active_is_false(): void
    {
        $userService = app(UserService::class);

        $userData = [
            'name' => 'Maria Santos',
            'email' => 'maria@teste.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'is_active' => false,
            'roles' => ['user']
        ];

        $user = $userService->createUser($userData);

        $this->assertNull($user->email_verified_at);
    }

    public function test_user_is_created_as_active_by_default_when_is_active_not_provided(): void
    {
        $userService = app(UserService::class);

        $userData = [
            'name' => 'Pedro Costa',
            'email' => 'pedro@teste.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'roles' => ['user']
        ];

        $user = $userService->createUser($userData);

        // Por padrão, deve ser inativo (null) se não especificado
        $this->assertNull($user->email_verified_at);
    }
}
