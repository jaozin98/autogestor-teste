<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'is_active' => $this->is_active,
            'products_count' => $this->whenCounted('products'),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'created_at_formatted' => $this->created_at?->format('d/m/Y H:i'),
            'updated_at_formatted' => $this->updated_at?->format('d/m/Y H:i'),
            'created_at_human' => $this->created_at?->diffForHumans(),
            'updated_at_human' => $this->updated_at?->diffForHumans(),

            // Relationships
            'products' => ProductResource::collection($this->whenLoaded('products')),

            // Links
            'links' => [
                'self' => route('categories.show', $this->id),
                'edit' => route('categories.edit', $this->id),
                'delete' => route('categories.destroy', $this->id),
                'toggle_status' => route('categories.toggle-status', $this->id),
            ],
        ];
    }
}
