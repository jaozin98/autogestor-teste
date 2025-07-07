<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
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
            'country_of_origin' => $this->country_of_origin,
            'founded_year' => $this->founded_year,
            'website' => $this->website,
            'description' => $this->description,
            'age' => $this->age,
            'formatted_website' => $this->formatted_website,
            'products_count' => $this->products_count,
            'display_name' => $this->display_name,
            'has_products' => $this->hasProducts(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'created_at_formatted' => $this->created_at?->format('d/m/Y H:i:s'),
            'updated_at_formatted' => $this->updated_at?->format('d/m/Y H:i:s'),
            'created_at_human' => $this->created_at?->diffForHumans(),

            // Relacionamentos (quando carregados)
            'products' => ProductResource::collection($this->whenLoaded('products')),

            // Links
            'links' => [
                'self' => route('brands.show', $this->id),
                'edit' => route('brands.edit', $this->id),
                'delete' => route('brands.destroy', $this->id),
            ],
        ];
    }
}
