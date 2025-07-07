<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        Log::info('Categoria criada via Observer', [
            'category_id' => $category->id,
            'name' => $category->name,
            'is_active' => $category->is_active,
            'user_id' => Auth::id() ?? null,
        ]);

        $this->clearCache();
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
        Log::info('Categoria atualizada via Observer', [
            'category_id' => $category->id,
            'name' => $category->name,
            'changes' => $category->getChanges(),
            'user_id' => Auth::id() ?? null,
        ]);

        $this->clearCache();
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        Log::info('Categoria excluída via Observer', [
            'category_id' => $category->id,
            'name' => $category->name,
            'user_id' => Auth::id() ?? null,
        ]);

        $this->clearCache();
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        Log::info('Categoria restaurada via Observer', [
            'category_id' => $category->id,
            'name' => $category->name,
            'user_id' => Auth::id() ?? null,
        ]);

        $this->clearCache();
    }

    /**
     * Handle the Category "force deleted" event.
     */
    public function forceDeleted(Category $category): void
    {
        Log::info('Categoria excluída permanentemente via Observer', [
            'category_id' => $category->id,
            'name' => $category->name,
            'user_id' => Auth::id() ?? null,
        ]);

        $this->clearCache();
    }

    /**
     * Clear all category-related cache
     */
    private function clearCache(): void
    {
        Cache::forget('categories.stats');
        Cache::forget('categories.active.select');

        // Clear paginated cache
        for ($i = 1; $i <= 10; $i++) {
            Cache::forget("categories.all.15.{$i}");
        }
    }
}
