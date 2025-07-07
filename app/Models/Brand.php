<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country_of_origin',
        'founded_year',
        'website',
        'description',
        'is_active',
    ];

    protected $casts = [
        'founded_year' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'age',
        'formatted_website',
        'products_count',
    ];

    /**
     * Get the products for the brand.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the brand's age in years.
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->founded_year) {
            return null;
        }

        return now()->year - $this->founded_year;
    }

    /**
     * Get formatted website URL.
     */
    public function getFormattedWebsiteAttribute(): ?string
    {
        if (!$this->website) {
            return null;
        }

        return parse_url($this->website, PHP_URL_HOST) ?? $this->website;
    }

    /**
     * Get products count.
     */
    public function getProductsCountAttribute(): int
    {
        return $this->products()->count();
    }

    /**
     * Scope a query to only include brands with products.
     */
    public function scopeWithProducts(Builder $query): Builder
    {
        return $query->has('products');
    }

    /**
     * Scope a query to only include brands without products.
     */
    public function scopeWithoutProducts(Builder $query): Builder
    {
        return $query->doesntHave('products');
    }

    /**
     * Scope a query to search brands by name or country.
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('country_of_origin', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Scope a query to filter by country.
     */
    public function scopeByCountry(Builder $query, string $country): Builder
    {
        return $query->where('country_of_origin', $country);
    }

    /**
     * Scope a query to filter by founded year range.
     */
    public function scopeFoundedBetween(Builder $query, int $startYear, int $endYear): Builder
    {
        return $query->whereBetween('founded_year', [$startYear, $endYear]);
    }

    /**
     * Check if brand has products.
     */
    public function hasProducts(): bool
    {
        return $this->products()->exists();
    }

    /**
     * Get the brand's display name with country.
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->country_of_origin) {
            return "{$this->name} ({$this->country_of_origin})";
        }

        return $this->name;
    }
}
