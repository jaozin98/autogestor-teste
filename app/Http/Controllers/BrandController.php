<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use App\Contracts\Services\BrandServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Exception;

class BrandController extends Controller
{
    public function __construct(
        private readonly BrandServiceInterface $brandService
    ) {
        $this->middleware('auth');
        $this->middleware('permission:brands.view')->only(['index', 'show']);
        $this->middleware('permission:brands.create')->only(['create', 'store']);
        $this->middleware('permission:brands.edit')->only(['edit', 'update']);
        $this->middleware('permission:brands.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|RedirectResponse
    {
        try {
            $search = $request->get('search');
            $perPage = $request->get('per_page', 15);

            $brands = $search
                ? $this->brandService->searchBrands($search, $perPage)
                : $this->brandService->getAllBrands($perPage);

            $stats = $this->brandService->getBrandStats();

            return view('brands.index', compact('brands', 'stats', 'search', 'perPage'));
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao carregar marcas: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandRequest $request): RedirectResponse
    {
        try {
            $brand = $this->brandService->createBrand($request->validated());

            return redirect()
                ->route('brands.index')
                ->with('success', "Marca '{$brand->name}' criada com sucesso!");
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao criar marca: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand): View|RedirectResponse
    {
        try {
            $brand = $this->brandService->findBrand($brand->id);

            if (!$brand) {
                return back()->with('error', 'Marca não encontrada.');
            }

            return view('brands.show', compact('brand'));
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao carregar marca: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand): View|RedirectResponse
    {
        try {
            $brand = $this->brandService->findBrand($brand->id);

            if (!$brand) {
                return back()->with('error', 'Marca não encontrada.');
            }

            return view('brands.edit', compact('brand'));
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao carregar formulário: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrandRequest $request, Brand $brand): RedirectResponse
    {
        try {
            $updated = $this->brandService->updateBrand($brand, $request->validated());

            if ($updated) {
                return redirect()
                    ->route('brands.index')
                    ->with('success', "Marca '{$brand->name}' atualizada com sucesso!");
            }

            return back()->with('error', 'Erro ao atualizar marca.');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar marca: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand): RedirectResponse
    {
        try {
            $brandName = $brand->name;
            $deleted = $this->brandService->deleteBrand($brand);

            if ($deleted) {
                return redirect()
                    ->route('brands.index')
                    ->with('success', "Marca '{$brandName}' excluída com sucesso!");
            }

            return back()->with('error', 'Erro ao excluir marca.');
        } catch (Exception $e) {
            return redirect()
                ->route('brands.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Toggle brand active status
     */
    public function toggleStatus(Brand $brand): RedirectResponse
    {
        try {
            $this->brandService->toggleBrandStatus($brand);

            $status = $brand->is_active ? 'ativada' : 'desativada';
            return redirect()
                ->route('brands.index')
                ->with('success', "Marca '{$brand->name}' {$status} com sucesso!");
        } catch (Exception $e) {
            return redirect()
                ->route('brands.index')
                ->with('error', 'Erro ao alterar status da marca: ' . $e->getMessage());
        }
    }
}
