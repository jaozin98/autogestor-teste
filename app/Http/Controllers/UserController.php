<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Contracts\Services\UserServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Exception;

class UserController extends Controller
{
    public function __construct(
        private readonly UserServiceInterface $userService
    ) {
        $this->middleware('auth');
        // Restringir acesso apenas a administradores
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|RedirectResponse
    {
        try {
            $search = $request->get('search');
            $perPage = $request->get('per_page', 15);
            $role = $request->get('role');

            if ($search) {
                $users = $this->userService->searchUsers($search, $perPage);
            } elseif ($role) {
                $users = $this->userService->getUsersByRole($role, $perPage);
            } else {
                $users = $this->userService->getAllUsers($perPage);
            }

            $stats = $this->userService->getUserStats();
            $recentUsers = $this->userService->getRecentlyRegisteredUsers(5);

            return view('users.index', compact('users', 'stats', 'search', 'perPage', 'role', 'recentUsers'));
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao carregar usuários: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|RedirectResponse
    {
        try {
            $roles = \Spatie\Permission\Models\Role::orderBy('name')->get();
            return view('users.create', compact('roles'));
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao carregar formulário: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $roles = $data['roles'] ?? [];
            unset($data['roles']);

            $user = $this->userService->createUser($data);

            // Assign roles
            foreach ($roles as $role) {
                $this->userService->assignRoleToUser($user, $role);
            }

            return redirect()
                ->route('users.index')
                ->with('success', "Usuário '{$user->name}' criado com sucesso!");
        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erro ao criar usuário: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View|RedirectResponse
    {
        try {
            $user = $this->userService->findUser($user->id);

            if (!$user) {
                return back()->with('error', 'Usuário não encontrado.');
            }

            return view('users.show', compact('user'));
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao carregar usuário: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View|RedirectResponse
    {
        try {
            $user = $this->userService->findUser($user->id);

            if (!$user) {
                return back()->with('error', 'Usuário não encontrado.');
            }

            $roles = \Spatie\Permission\Models\Role::orderBy('name')->get();

            return view('users.edit', compact('user', 'roles'));
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao carregar formulário: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user): RedirectResponse
    {
        try {
            $data = $request->validated();
            $roles = $data['roles'] ?? [];
            unset($data['roles']);

            $updated = $this->userService->updateUser($user, $data);

            if ($updated) {
                // Sync roles
                $user->syncRoles($roles);

                // Limpar cache de permissões
                app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

                return redirect()
                    ->route('users.index')
                    ->with('success', "Usuário '{$user->name}' atualizado com sucesso!");
            }

            return back()->with('error', 'Erro ao atualizar usuário.');
        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erro ao atualizar usuário: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        try {
            $userName = $user->name;
            $deleted = $this->userService->deleteUser($user);

            if ($deleted) {
                return redirect()
                    ->route('users.index')
                    ->with('success', "Usuário '{$userName}' excluído com sucesso!");
            }

            return back()->with('error', 'Erro ao excluir usuário.');
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao excluir usuário: ' . $e->getMessage());
        }
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        try {
            $toggled = $this->userService->toggleUserStatus($user);

            if ($toggled) {
                $status = $user->email_verified_at ? 'ativado' : 'desativado';
                return back()->with('success', "Usuário '{$user->name}' {$status} com sucesso!");
            }

            return back()->with('error', 'Erro ao alterar status do usuário.');
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao alterar status: ' . $e->getMessage());
        }
    }

    /**
     * Reset user password
     */
    public function resetPassword(User $user): RedirectResponse
    {
        try {
            $newPassword = $this->userService->resetUserPassword($user);

            return back()->with('success', "Senha do usuário '{$user->name}' redefinida. Nova senha: {$newPassword}");
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao redefinir senha: ' . $e->getMessage());
        }
    }

    /**
     * Bulk update users
     */
    public function bulkUpdate(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'user_ids' => 'required|array',
                'user_ids.*' => 'exists:users,id',
                'action' => 'required|in:activate,deactivate,delete',
            ]);

            $userIds = $request->input('user_ids');
            $action = $request->input('action');

            switch ($action) {
                case 'activate':
                    $this->userService->bulkUpdateUsers($userIds, ['email_verified_at' => now()]);
                    $message = 'Usuários ativados com sucesso!';
                    break;
                case 'deactivate':
                    $this->userService->bulkUpdateUsers($userIds, ['email_verified_at' => null]);
                    $message = 'Usuários desativados com sucesso!';
                    break;
                case 'delete':
                    $this->userService->bulkDeleteUsers($userIds);
                    $message = 'Usuários excluídos com sucesso!';
                    break;
            }

            return redirect()
                ->route('users.index')
                ->with('success', $message);
        } catch (Exception $e) {
            return back()->with('error', 'Erro na operação em lote: ' . $e->getMessage());
        }
    }

    /**
     * API: Get users list
     */
    public function apiIndex(Request $request): JsonResponse
    {
        try {
            $search = $request->get('search');
            $perPage = $request->get('per_page', 15);

            $users = $search
                ? $this->userService->searchUsers($search, $perPage)
                : $this->userService->getAllUsers($perPage);

            return response()->json([
                'success' => true,
                'data' => UserResource::collection($users),
                'meta' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar usuários: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Get specific user
     */
    public function apiShow(User $user): JsonResponse
    {
        try {
            $user = $this->userService->findUser($user->id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não encontrado.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => new UserResource($user),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar usuário: ' . $e->getMessage(),
            ], 500);
        }
    }
}
