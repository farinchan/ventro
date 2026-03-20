<?php

namespace App\Http\Resources\Fnb;

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
            'image' => $this->image,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'variants' => $this->variants->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'name' => $variant->name,
                    'price' => $variant->price,
                ];
            }),
            'category' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'description' => $this->category->description,
            ] : null,
            'business' => $this->business ? [
                'id' => $this->business->id,
                'name' => $this->business->name,
                'description' => $this->business->description,
            ] : null,
        ];
    }
}
