<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\PermissionRequest;
use Exception;

class PermissionController extends Controller
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

        $query = Permission::with(['roles', 'users']);

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }



        $permissions = $query->orderBy('name')->get();

        // Calcular estatísticas
        $stats = [
            'total' => Permission::count(),
            'user' => Permission::where('name', 'like', 'user%')->count(),
            'product' => Permission::where('name', 'like', 'product%')->count(),
            'category' => Permission::where('name', 'like', 'category%')->count(),
            'brand' => Permission::where('name', 'like', 'brand%')->count(),
        ];

        return view('permissions.index', compact('permissions', 'stats', 'search', 'perPage'));
    }

    public function create(): View
    {
        return view('permissions.create');
    }

    public function store(PermissionRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $permission = Permission::create(['name' => $data['name']]);

            // Limpar cache de permissões
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            return redirect()->route('permissions.index')->with('success', "Permissão '{$permission->name}' criada com sucesso!");
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Erro ao criar permissão: ' . $e->getMessage());
        }
    }

    public function show(Permission $permission): View
    {
        return view('permissions.show', compact('permission'));
    }

    public function edit(Permission $permission): View
    {
        return view('permissions.edit', compact('permission'));
    }

    public function update(PermissionRequest $request, Permission $permission): RedirectResponse
    {
        try {
            $data = $request->validated();
            $permission->update(['name' => $data['name']]);

            // Limpar cache de permissões
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            return redirect()->route('permissions.index')->with('success', "Permissão '{$permission->name}' atualizada com sucesso!");
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Erro ao atualizar permissão: ' . $e->getMessage());
        }
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        try {
            $permissionName = $permission->name;
            $permission->delete();

            // Limpar cache de permissões
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            return redirect()->route('permissions.index')->with('success', "Permissão '{$permissionName}' excluída com sucesso!");
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao excluir permissão: ' . $e->getMessage());
        }
    }
}
