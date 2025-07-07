<?php

namespace App\Http\Controllers;

use App\Contracts\Services\ProductServiceInterface;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Exception;

class ProductController extends Controller
{
    public function __construct(
        private ProductServiceInterface $productService
    ) {
        $this->middleware('permission:products.view')->only(['index', 'show']);
        $this->middleware('permission:products.create')->only(['create', 'store']);
        $this->middleware('permission:products.edit')->only(['edit', 'update', 'toggleStatus', 'updateStock', 'bulkUpdate']);
        $this->middleware('permission:products.delete')->only(['destroy', 'bulkDelete']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $search = $request->get('search');
            $perPage = $request->get('per_page', 15);

            if ($search) {
                $products = $this->productService->searchProducts($search, $perPage);
            } else {
                $products = $this->productService->getAllProducts($perPage);
            }

            $stats = $this->productService->getProductStats();
            $categories = Category::active()->orderBy('name')->get();
            $brands = Brand::orderBy('name')->get();

            return view('products.index', compact('products', 'stats', 'search', 'perPage', 'categories', 'brands'));
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao carregar produtos: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $categories = Category::active()->orderBy('name')->get();
            $brands = Brand::orderBy('name')->get();

            return view('products.create', compact('categories', 'brands'));
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao carregar formulário: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        try {
            $product = $this->productService->createProduct($request->validated());

            return redirect()
                ->route('products.index')
                ->with('success', "Produto '{$product->name}' criado com sucesso!");
        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erro ao criar produto: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        try {
            $product = $this->productService->findProduct($product->id);

            if (!$product) {
                return back()->with('error', 'Produto não encontrado.');
            }

            return view('products.show', compact('product'));
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao carregar produto: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): \Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        try {
            $product = $this->productService->findProduct($product->id);

            if (!$product) {
                return back()->with('error', 'Produto não encontrado.');
            }

            $categories = Category::active()->orderBy('name')->get();
            $brands = Brand::orderBy('name')->get();

            return view('products.edit', compact('product', 'categories', 'brands'));
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao carregar formulário: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        try {
            $updated = $this->productService->updateProduct($product, $request->validated());

            if ($updated) {
                return redirect()
                    ->route('products.index')
                    ->with('success', "Produto '{$product->name}' atualizado com sucesso!");
            }

            return back()->with('error', 'Erro ao atualizar produto.');
        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erro ao atualizar produto: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        try {
            $productName = $product->name;
            $deleted = $this->productService->deleteProduct($product);

            if ($deleted) {
                return redirect()
                    ->route('products.index')
                    ->with('success', "Produto '{$productName}' excluído com sucesso!");
            }

            return back()->with('error', 'Erro ao excluir produto.');
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao excluir produto: ' . $e->getMessage());
        }
    }

    /**
     * Toggle product active status
     */
    public function toggleStatus(Product $product): RedirectResponse
    {
        try {

            $toggled = $this->productService->toggleProductStatus($product);

            if ($toggled) {
                $status = $product->is_active ? 'ativado' : 'desativado';
                return back()->with('success', "Produto '{$product->name}' {$status} com sucesso!");
            }

            return back()->with('error', 'Erro ao alterar status do produto.');
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao alterar status: ' . $e->getMessage());
        }
    }

    /**
     * Update product stock
     */
    public function updateStock(Request $request, Product $product): RedirectResponse
    {
        try {

            $request->validate([
                'quantity' => 'required|integer|min:1',
                'operation' => 'required|in:add,subtract,set'
            ]);

            $updated = $this->productService->updateStock(
                $product,
                $request->quantity,
                $request->operation
            );

            if ($updated) {
                return back()->with('success', 'Estoque atualizado com sucesso!');
            }

            return back()->with('error', 'Erro ao atualizar estoque.');
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao atualizar estoque: ' . $e->getMessage());
        }
    }

    /**
     * Get products by category
     */
    public function byCategory(Request $request, int $categoryId): \Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $products = $this->productService->getProductsByCategory($categoryId, $perPage);
            $category = Category::findOrFail($categoryId);

            return view('products.by-category', compact('products', 'category'));
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao carregar produtos da categoria: ' . $e->getMessage());
        }
    }

    /**
     * Get products by brand
     */
    public function byBrand(Request $request, int $brandId): \Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $products = $this->productService->getProductsByBrand($brandId, $perPage);
            $brand = Brand::findOrFail($brandId);

            return view('products.by-brand', compact('products', 'brand'));
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao carregar produtos da marca: ' . $e->getMessage());
        }
    }

    /**
     * Get low stock products
     */
    public function lowStock(Request $request): \Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $products = $this->productService->getLowStockProducts($perPage);

            return view('products.low-stock', compact('products'));
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao carregar produtos com estoque baixo: ' . $e->getMessage());
        }
    }

    /**
     * Get out of stock products
     */
    public function outOfStock(Request $request): \Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $products = $this->productService->getOutOfStockProducts($perPage);

            return view('products.out-of-stock', compact('products'));
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao carregar produtos sem estoque: ' . $e->getMessage());
        }
    }

    /**
     * Bulk update products
     */
    public function bulkUpdate(Request $request): RedirectResponse
    {
        try {

            $request->validate([
                'product_ids' => 'required|array|min:1',
                'product_ids.*' => 'integer|exists:products,id',
                'data' => 'required|array'
            ]);

            $updatedCount = $this->productService->bulkUpdateProducts(
                $request->product_ids,
                $request->data
            );

            return back()->with('success', "{$updatedCount} produtos atualizados com sucesso!");
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao atualizar produtos em lote: ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete products
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        try {

            $request->validate([
                'product_ids' => 'required|array|min:1',
                'product_ids.*' => 'integer|exists:products,id'
            ]);

            $deletedCount = $this->productService->bulkDeleteProducts($request->product_ids);

            return back()->with('success', "{$deletedCount} produtos excluídos com sucesso!");
        } catch (Exception $e) {
            return back()->with('error', 'Erro ao excluir produtos em lote: ' . $e->getMessage());
        }
    }

    /**
     * API endpoint for products
     */
    public function apiIndex(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $products = $this->productService->getAllProducts($perPage);

            return response()->json([
                'success' => true,
                'data' => ProductResource::collection($products),
                'meta' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar produtos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint for single product
     */
    public function apiShow(Product $product): JsonResponse
    {
        try {
            $product = $this->productService->findProduct($product->id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produto não encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => new ProductResource($product)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar produto',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
