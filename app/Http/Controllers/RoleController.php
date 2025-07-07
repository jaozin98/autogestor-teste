<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\RoleRequest;
use Exception;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index(Request $request): View
    {
        $search = $request->get('search');
        $perPage = $request->get('per_page', 15);
        $type = $request->get('type');

        $query = Role::with(['permissions', 'users']);

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($type) {
            if ($type === 'custom') {
                $query->whereNotIn('name', ['admin', 'user']);
            } else {
                $query->where('name', $type);
            }
        }

        $roles = $query->orderBy('name')->get();

        // Calcular estatísticas
        $stats = [
            'total' => Role::count(),
            'admin' => Role::where('name', 'admin')->count(),
            'user' => Role::where('name', 'user')->count(),
            'custom' => Role::whereNotIn('name', ['admin', 'user'])->count(),
        ];

        return view('roles.index', compact('roles', 'stats', 'search', 'perPage', 'type'));
    }

    public function create(): View
    {
        $permissions = Permission::orderBy('name')->get();
        return view('roles.create', compact('permissions'));
    }

    public function store(RoleRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $role = Role::create(['name' => $data['name']]);
            $role->syncPermissions($data['permissions'] ?? []);

            // Limpar cache de permissões
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            return redirect()->route('roles.index')->with('success', "Role '{$role->name}' criada com sucesso!");
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Erro ao criar role: ' . $e->getMessage());
        }
    }

    public function show(Role $role): View
    {
        $role->load('permissions');
        return view('roles.show', compact('role'));
    }

    public function edit(Role $role): View
    {
        $permissions = Permission::orderBy('name')->get();
        $role->load('permissions');
        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(RoleRequest $request, Role $role): RedirectResponse
    {
        try {
            $data = $request->validated();
            $role->update(['name' => $data['name']]);
            $role->syncPermissions($data['permissions'] ?? []);

            // Limpar cache de permissões
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            return redirect()->route('roles.index')->with('success', "Role '{$role->name}' atualizada com sucesso!");
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Erro ao atualizar role: ' . $e->getMessage());
        }
    }

    public function destroy(Role $role): RedirectResponse
    {
        try {
            $roleName = $role->name;
            $role->delete();

            // Limpar cache de permissões
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            return redirect()->route('roles.index')->with('success', "Role '{$roleName}' excluída com sucesso!");
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao excluir role: ' . $e->getMessage());
        }
    }
}
