<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'cost_price' => $this->cost_price,
            'sale_price' => $this->sale_price,
            'stock' => $this->stock,
            'min_stock' => $this->min_stock,
            'max_stock' => $this->max_stock,
            'sku' => $this->sku,
            'barcode' => $this->barcode,
            'is_active' => $this->is_active,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'weight' => $this->weight,
            'height' => $this->height,
            'width' => $this->width,
            'length' => $this->length,
            'specifications' => $this->specifications,
            'images' => $this->images,
            'last_purchase_date' => $this->last_purchase_date?->toISOString(),
            'last_sale_date' => $this->last_sale_date?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),

            // Computed attributes
            'formatted_price' => $this->formatted_price,
            'formatted_cost_price' => $this->formatted_cost_price,
            'formatted_sale_price' => $this->formatted_sale_price,
            'stock_status' => $this->stock_status,
            'profit_margin' => $this->profit_margin,
            'total_value' => $this->total_value,
            'dimensions' => $this->dimensions,
            'main_image_url' => $this->main_image_url,

            // Relationships (when loaded)
            'category' => $this->whenLoaded('category', function () {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                    'description' => $this->category->description,
                    'is_active' => $this->category->is_active,
                ];
            }),
            'brand' => $this->whenLoaded('brand', function () {
                return [
                    'id' => $this->brand->id,
                    'name' => $this->brand->name,
                    'country_of_origin' => $this->brand->country_of_origin,
                    'description' => $this->brand->description,
                ];
            }),

            // Stock information
            'is_in_stock' => $this->isInStock(),
            'is_low_stock' => $this->isLowStock(),
            'is_out_of_stock' => $this->isOutOfStock(),
            'has_stock' => $this->hasStock(1),

            // URLs
            'image_urls' => $this->getImageUrls(),
            'edit_url' => route('products.edit', $this->id),
            'show_url' => route('products.show', $this->id),
            'delete_url' => route('products.destroy', $this->id),
        ];
    }
}
