<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Contracts\Services\CategoryServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Exception;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryServiceInterface $categoryService
    ) {
        $this->middleware('auth');
        $this->middleware('permission:categories.view')->only(['index', 'show']);
        $this->middleware('permission:categories.create')->only(['create', 'store']);
        $this->middleware('permission:categories.edit')->only(['edit', 'update']);
        $this->middleware('permission:categories.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $perPage = $request->get('per_page', 15);

        $categories = $search
            ? $this->categoryService->searchCategories($search, $perPage)
            : $this->categoryService->getAllCategories($perPage);

        $stats = $this->categoryService->getCategoryStats();

        return view('categories.index', compact('categories', 'stats', 'search', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request): RedirectResponse
    {
        try {
            $category = $this->categoryService->createCategory($request->validated());

            return redirect()
                ->route('categories.index')
                ->with('success', "Categoria '{$category->name}' criada com sucesso!");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao criar categoria: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): View
    {
        $category = $this->categoryService->findCategory($category->id);

        if (!$category) {
            abort(404, 'Categoria não encontrada.');
        }

        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): View
    {
        $category = $this->categoryService->findCategory($category->id);

        if (!$category) {
            abort(404, 'Categoria não encontrada.');
        }

        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        try {
            $this->categoryService->updateCategory($category, $request->validated());

            return redirect()
                ->route('categories.index')
                ->with('success', "Categoria '{$category->name}' atualizada com sucesso!");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar categoria: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        try {
            $categoryName = $category->name;
            $this->categoryService->deleteCategory($category);

            return redirect()
                ->route('categories.index')
                ->with('success', "Categoria '{$categoryName}' excluída com sucesso!");
        } catch (\Exception $e) {
            return redirect()
                ->route('categories.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Toggle category active status
     */
    public function toggleStatus(Category $category): RedirectResponse
    {
        try {
            $this->categoryService->toggleCategoryStatus($category);

            $status = $category->is_active ? 'ativada' : 'desativada';
            return redirect()
                ->route('categories.index')
                ->with('success', "Categoria '{$category->name}' {$status} com sucesso!");
        } catch (\Exception $e) {
            return redirect()
                ->route('categories.index')
                ->with('error', 'Erro ao alterar status da categoria: ' . $e->getMessage());
        }
    }
}
