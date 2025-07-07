<?php

namespace Tests\Feature;

use App\Models\User;
use App\Contracts\Services\UserServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    private UserServiceInterface $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userService = app(UserServiceInterface::class);

        // Criar roles para os testes
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
    }

    public function test_can_create_user(): void
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $user = $this->userService->createUser($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_can_update_user(): void
    {
        $user = User::factory()->create();

        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ];

        $result = $this->userService->updateUser($user, $updateData);

        $this->assertTrue($result);
        $user->refresh();
        $this->assertEquals('Updated Name', $user->name);
        $this->assertEquals('updated@example.com', $user->email);
    }

    public function test_can_delete_user(): void
    {
        $user = User::factory()->create();

        $result = $this->userService->deleteUser($user);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_cannot_delete_self(): void
    {
        $user = User::factory()->create();

        // Simular que o usuário atual é o mesmo que está sendo excluído
        $this->actingAs($user);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Não é possível excluir o próprio usuário.');

        $this->userService->deleteUser($user);
    }

    public function test_can_search_users(): void
    {
        User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);
        User::factory()->create(['name' => 'Jane Smith', 'email' => 'jane@example.com']);
        User::factory()->create(['name' => 'Bob Johnson', 'email' => 'bob@example.com']);

        $results = $this->userService->searchUsers('john');

        // Verificar se encontrou pelo menos o usuário John Doe
        $johnDoe = $results->where('name', 'John Doe')->first();
        $this->assertNotNull($johnDoe);
        $this->assertEquals('John Doe', $johnDoe->name);
    }

    public function test_can_get_users_by_role(): void
    {
        $adminUser = User::factory()->create();
        $adminUser->assignRole('admin');

        $regularUser = User::factory()->create();
        $regularUser->assignRole('user');

        $adminUsers = $this->userService->getUsersByRole('admin');

        $this->assertEquals(1, $adminUsers->count());
        $this->assertEquals($adminUser->id, $adminUsers->first()->id);
    }

    public function test_can_toggle_user_status(): void
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $result = $this->userService->toggleUserStatus($user);

        $this->assertTrue($result);
        $user->refresh();
        $this->assertNotNull($user->email_verified_at);

        // Toggle novamente para desativar
        $result = $this->userService->toggleUserStatus($user);
        $this->assertTrue($result);
        $user->refresh();
        $this->assertNull($user->email_verified_at);
    }

    public function test_can_assign_role_to_user(): void
    {
        $user = User::factory()->create();

        $result = $this->userService->assignRoleToUser($user, 'admin');

        $this->assertTrue($result);
        $this->assertTrue($user->hasRole('admin'));
    }

    public function test_can_remove_role_from_user(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $result = $this->userService->removeRoleFromUser($user, 'admin');

        $this->assertTrue($result);
        $this->assertFalse($user->hasRole('admin'));
    }

    public function test_can_get_user_stats(): void
    {
        User::factory()->create(['email_verified_at' => now()]);
        User::factory()->create(['email_verified_at' => null]);

        $adminUser = User::factory()->create(['email_verified_at' => now()]);
        $adminUser->assignRole('admin');

        $stats = $this->userService->getUserStats();

        $this->assertEquals(3, $stats['total']);
        $this->assertEquals(2, $stats['active']);
        $this->assertEquals(1, $stats['inactive']);
        $this->assertEquals(1, $stats['admin']);
    }

    public function test_can_get_recently_registered_users(): void
    {
        User::factory()->count(5)->create();

        $recentUsers = $this->userService->getRecentlyRegisteredUsers(3);

        $this->assertEquals(3, $recentUsers->count());
    }

    public function test_can_change_user_password(): void
    {
        $user = User::factory()->create();
        $newPassword = 'newpassword123';

        $result = $this->userService->changeUserPassword($user, $newPassword);

        $this->assertTrue($result);
        $user->refresh();
        $this->assertTrue(Hash::check($newPassword, $user->password));
    }

    public function test_can_reset_user_password(): void
    {
        $user = User::factory()->create();

        $newPassword = $this->userService->resetUserPassword($user);

        $this->assertIsString($newPassword);
        $this->assertEquals(12, strlen($newPassword));
        $user->refresh();
        $this->assertTrue(Hash::check($newPassword, $user->password));
    }

    public function test_validates_user_data_on_create(): void
    {
        $this->expectException(\Exception::class);

        $invalidData = [
            'name' => '', // Nome vazio
            'email' => 'invalid-email', // Email inválido
        ];

        $this->userService->createUser($invalidData);
    }

    public function test_validates_user_data_on_update(): void
    {
        $user = User::factory()->create();

        $this->expectException(\Exception::class);

        $invalidData = [
            'email' => 'invalid-email', // Email inválido
        ];

        $this->userService->updateUser($user, $invalidData);
    }

    public function test_can_bulk_update_users(): void
    {
        $users = User::factory()->count(3)->create(['email_verified_at' => null]);
        $userIds = $users->pluck('id')->toArray();

        $result = $this->userService->bulkUpdateUsers($userIds, ['email_verified_at' => now()]);

        $this->assertEquals(3, $result);

        foreach ($users as $user) {
            $user->refresh();
            $this->assertNotNull($user->email_verified_at);
        }
    }

    public function test_can_bulk_delete_users(): void
    {
        $users = User::factory()->count(3)->create();
        $userIds = $users->pluck('id')->toArray();

        $result = $this->userService->bulkDeleteUsers($userIds);

        $this->assertEquals(3, $result);

        foreach ($userIds as $userId) {
            $this->assertDatabaseMissing('users', ['id' => $userId]);
        }
    }

    public function test_cannot_bulk_delete_self(): void
    {
        $currentUser = User::factory()->create();
        $this->actingAs($currentUser);

        $otherUsers = User::factory()->count(2)->create();
        $userIds = array_merge([$currentUser->id], $otherUsers->pluck('id')->toArray());

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Não é possível excluir o próprio usuário.');

        $this->userService->bulkDeleteUsers($userIds);
    }
}
