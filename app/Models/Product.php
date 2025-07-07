<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'cost_price',
        'sale_price',
        'stock',
        'min_stock',
        'max_stock',
        'sku',
        'barcode',
        'is_active',
        'category_id',
        'brand_id',
        'weight',
        'height',
        'width',
        'length',
        'specifications',
        'images',
        'last_purchase_date',
        'last_sale_date'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock' => 'integer',
        'min_stock' => 'integer',
        'max_stock' => 'integer',
        'is_active' => 'boolean',
        'weight' => 'decimal:3',
        'height' => 'decimal:2',
        'width' => 'decimal:2',
        'length' => 'decimal:2',
        'specifications' => 'array',
        'images' => 'array',
        'last_purchase_date' => 'datetime',
        'last_sale_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    protected $attributes = [
        'is_active' => true,
        'stock' => 0,
        'min_stock' => 0,
    ];

    protected $appends = [
        'formatted_price',
        'formatted_cost_price',
        'formatted_sale_price',
        'stock_status',
        'profit_margin',
        'total_value',
        'dimensions',
        'main_image_url'
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeOutOfStock(Builder $query): Builder
    {
        return $query->where('stock', 0);
    }

    public function scopeLowStock(Builder $query): Builder
    {
        return $query->whereRaw('stock <= min_stock')
            ->where('stock', '>', 0);
    }

    public function scopeByCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByBrand(Builder $query, int $brandId): Builder
    {
        return $query->where('brand_id', $brandId);
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%")
                ->orWhere('barcode', 'like', "%{$search}%");
        });
    }

    public function scopePriceRange(Builder $query, float $min, float $max): Builder
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    public function scopeRecentlyAdded(Builder $query, int $days = 30): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeRecentlyUpdated(Builder $query, int $days = 30): Builder
    {
        return $query->where('updated_at', '>=', now()->subDays($days));
    }

    // Accessors
    public function getFormattedPriceAttribute(): string
    {
        return 'R$ ' . number_format($this->price, 2, ',', '.');
    }

    public function getFormattedCostPriceAttribute(): ?string
    {
        return $this->cost_price ? 'R$ ' . number_format($this->cost_price, 2, ',', '.') : null;
    }

    public function getFormattedSalePriceAttribute(): ?string
    {
        return $this->sale_price ? 'R$ ' . number_format($this->sale_price, 2, ',', '.') : null;
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stock === 0) {
            return 'Sem estoque';
        }

        if ($this->stock <= $this->min_stock) {
            return 'Estoque baixo';
        }

        return 'Em estoque';
    }

    public function getProfitMarginAttribute(): ?float
    {
        if (!$this->cost_price || $this->cost_price <= 0) {
            return null;
        }

        return round((($this->price - $this->cost_price) / $this->cost_price) * 100, 2);
    }

    public function getTotalValueAttribute(): float
    {
        return $this->price * $this->stock;
    }

    public function getDimensionsAttribute(): string
    {
        $dimensions = [];

        if ($this->length) $dimensions[] = $this->length . 'cm';
        if ($this->width) $dimensions[] = $this->width . 'cm';
        if ($this->height) $dimensions[] = $this->height . 'cm';

        return !empty($dimensions) ? implode(' x ', $dimensions) : 'N/A';
    }

    public function getMainImageUrlAttribute(): ?string
    {
        if (!$this->images || empty($this->images)) {
            return null;
        }

        $mainImage = is_array($this->images) ? $this->images[0] : $this->images;

        if (filter_var($mainImage, FILTER_VALIDATE_URL)) {
            return $mainImage;
        }

        return asset('storage/' . $mainImage);
    }

    // Mutators
    public function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = ucwords(strtolower($value));
    }

    public function setSkuAttribute(?string $value): void
    {
        $this->attributes['sku'] = $value ? strtoupper($value) : null;
    }

    public function setBarcodeAttribute(?string $value): void
    {
        $this->attributes['barcode'] = $value ? preg_replace('/[^0-9]/', '', $value) : null;
    }

    public function setPriceAttribute(float $value): void
    {
        $this->attributes['price'] = round($value, 2);
    }

    public function setCostPriceAttribute(?float $value): void
    {
        $this->attributes['cost_price'] = $value ? round($value, 2) : null;
    }

    public function setSalePriceAttribute(?float $value): void
    {
        $this->attributes['sale_price'] = $value ? round($value, 2) : null;
    }

    // Methods
    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    public function isLowStock(): bool
    {
        return $this->stock > 0 && $this->stock <= $this->min_stock;
    }

    public function isOutOfStock(): bool
    {
        return $this->stock === 0;
    }

    public function hasStock(int $quantity): bool
    {
        return $this->stock >= $quantity;
    }

    public function addStock(int $quantity): bool
    {
        $this->stock += $quantity;
        return $this->save();
    }

    public function removeStock(int $quantity): bool
    {
        if (!$this->hasStock($quantity)) {
            return false;
        }

        $this->stock -= $quantity;
        return $this->save();
    }

    public function setStock(int $quantity): bool
    {
        $this->stock = max(0, $quantity);
        return $this->save();
    }

    public function updateLastPurchaseDate(): bool
    {
        $this->last_purchase_date = now();
        return $this->save();
    }

    public function updateLastSaleDate(): bool
    {
        $this->last_sale_date = now();
        return $this->save();
    }

    public function toggleActive(): bool
    {
        $this->is_active = !$this->is_active;
        return $this->save();
    }

    public function getImageUrls(): array
    {
        if (!$this->images || empty($this->images)) {
            return [];
        }

        return array_map(function ($image) {
            if (filter_var($image, FILTER_VALIDATE_URL)) {
                return $image;
            }
            return asset('storage/' . $image);
        }, is_array($this->images) ? $this->images : [$this->images]);
    }

    public function getSpecification(string $key, $default = null)
    {
        if (!$this->specifications || !is_array($this->specifications)) {
            return $default;
        }

        return $this->specifications[$key] ?? $default;
    }

    public function setSpecification(string $key, $value): bool
    {
        $specifications = $this->specifications ?? [];
        $specifications[$key] = $value;
        $this->specifications = $specifications;
        return $this->save();
    }

    // Events
    protected static function booted(): void
    {
        static::creating(function ($product) {
            if (empty($product->sku)) {
                $product->sku = self::generateSku($product->name, $product->category_id);
            }
        });

        static::updating(function ($product) {
            // Update sale_price if not set and cost_price is available
            if (empty($product->sale_price) && $product->cost_price) {
                $product->sale_price = $product->price;
            }
        });

        static::deleted(function ($product) {
            // Clean up images when product is deleted
            if ($product->images) {
                foreach ($product->images as $image) {
                    if (!filter_var($image, FILTER_VALIDATE_URL)) {
                        Storage::disk('public')->delete($image);
                    }
                }
            }
        });
    }

    // Static methods
    public static function generateSku(string $name, int $categoryId): string
    {
        $category = Category::find($categoryId);
        $categoryPrefix = $category ? strtoupper(substr($category->name, 0, 3)) : 'PRD';

        $nameSlug = \Illuminate\Support\Str::slug($name);
        $namePrefix = strtoupper(substr($nameSlug, 0, 3));

        $timestamp = now()->format('ymd');
        $random = strtoupper(\Illuminate\Support\Str::random(3));

        return "{$categoryPrefix}-{$namePrefix}-{$timestamp}-{$random}";
    }
}
