<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        Log::info('Produto criado via Observer', [
            'product_id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'user_id' => Auth::id() ?? null,
        ]);

        $this->clearCache();
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        Log::info('Produto atualizado via Observer', [
            'product_id' => $product->id,
            'name' => $product->name,
            'changes' => $product->getChanges(),
            'user_id' => Auth::id() ?? null,
        ]);

        $this->clearCache();
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        Log::info('Produto excluído via Observer', [
            'product_id' => $product->id,
            'name' => $product->name,
            'user_id' => Auth::id() ?? null,
        ]);

        $this->clearCache();
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        Log::info('Produto restaurado via Observer', [
            'product_id' => $product->id,
            'name' => $product->name,
            'user_id' => Auth::id() ?? null,
        ]);

        $this->clearCache();
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        Log::info('Produto excluído permanentemente via Observer', [
            'product_id' => $product->id,
            'name' => $product->name,
            'user_id' => Auth::id() ?? null,
        ]);

        $this->clearCache();
    }

    /**
     * Clear all product-related cache
     */
    private function clearCache(): void
    {
        Cache::forget('products.stats');
        Cache::forget('products.active.select');

        // Clear paginated cache
        for ($i = 1; $i <= 10; $i++) {
            Cache::forget("products.all.15.{$i}");
        }
    }
}
