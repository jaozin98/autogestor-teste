<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    protected $appends = [
        'products_count',
        'status_label',
        'created_at_formatted',
        'updated_at_formatted',
    ];

    /**
     * Get the products for the category.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive categories.
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope a query to only include categories with products.
     */
    public function scopeWithProducts(Builder $query): Builder
    {
        return $query->has('products');
    }

    /**
     * Scope a query to only include categories without products.
     */
    public function scopeWithoutProducts(Builder $query): Builder
    {
        return $query->doesntHave('products');
    }

    /**
     * Scope a query to search categories by name or description.
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus(Builder $query, bool $isActive): Builder
    {
        return $query->where('is_active', $isActive);
    }

    /**
     * Get products count.
     */
    public function getProductsCountAttribute(): int
    {
        return $this->products()->count();
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->is_active ? 'Ativo' : 'Inativo';
    }

    /**
     * Get formatted created at date.
     */
    public function getCreatedAtFormattedAttribute(): string
    {
        return $this->created_at?->format('d/m/Y H:i:s') ?? '';
    }

    /**
     * Get formatted updated at date.
     */
    public function getUpdatedAtFormattedAttribute(): string
    {
        return $this->updated_at?->format('d/m/Y H:i:s') ?? '';
    }

    /**
     * Check if category has products.
     */
    public function hasProducts(): bool
    {
        return $this->products()->exists();
    }

    /**
     * Get the category's display name with status.
     */
    public function getDisplayNameAttribute(): string
    {
        $status = $this->is_active ? 'Ativo' : 'Inativo';
        return "{$this->name} ({$status})";
    }

    /**
     * Toggle category active status.
     */
    public function toggleActive(): bool
    {
        $this->is_active = !$this->is_active;
        return $this->save();
    }

    /**
     * Check if category is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Check if category is inactive.
     */
    public function isInactive(): bool
    {
        return !$this->is_active;
    }
}
