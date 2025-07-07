<?php

namespace App\Observers;

use App\Models\Brand;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class BrandObserver
{
    /**
     * Handle the Brand "created" event.
     */
    public function created(Brand $brand): void
    {
        Log::info('Marca criada via Observer', [
            'brand_id' => $brand->id,
            'name' => $brand->name,
            'country_of_origin' => $brand->country_of_origin,
            'user_id' => Auth::id() ?? null,
        ]);

        $this->clearCache();
    }

    /**
     * Handle the Brand "updated" event.
     */
    public function updated(Brand $brand): void
    {
        Log::info('Marca atualizada via Observer', [
            'brand_id' => $brand->id,
            'name' => $brand->name,
            'changes' => $brand->getChanges(),
            'user_id' => Auth::id() ?? null,
        ]);

        $this->clearCache();
    }

    /**
     * Handle the Brand "deleted" event.
     */
    public function deleted(Brand $brand): void
    {
        Log::info('Marca excluída via Observer', [
            'brand_id' => $brand->id,
            'name' => $brand->name,
            'user_id' => Auth::id() ?? null,
        ]);

        $this->clearCache();
    }

    /**
     * Handle the Brand "restored" event.
     */
    public function restored(Brand $brand): void
    {
        Log::info('Marca restaurada via Observer', [
            'brand_id' => $brand->id,
            'name' => $brand->name,
            'user_id' => Auth::id() ?? null,
        ]);

        $this->clearCache();
    }

    /**
     * Handle the Brand "force deleted" event.
     */
    public function forceDeleted(Brand $brand): void
    {
        Log::info('Marca excluída permanentemente via Observer', [
            'brand_id' => $brand->id,
            'name' => $brand->name,
            'user_id' => Auth::id() ?? null,
        ]);

        $this->clearCache();
    }

    /**
     * Clear all brand-related cache
     */
    private function clearCache(): void
    {
        Cache::forget('brands.stats');
        Cache::forget('brands.select');

        // Clear paginated cache
        for ($i = 1; $i <= 10; $i++) {
            Cache::forget("brands.all.15.{$i}");
        }
    }
}
